<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BaseActionController;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * 上传
 */
class UploadController extends BaseActionController {

    //网站路径
    private $web_path;
    //资源路径
    private $web_res_path;
    private $fileField;
    private $fileNewName;
    private $file;
    private $config;
    private $oriName; //原始文件名
    private $fileName; //新文件名
    private $fullName; //完整文件名,即从当前配置目录开始的URL
    private $smallFullName;
    private $fileNewSmallName;
    private $filePath; //完整文件名,即从当前配置目录开始的URL
    private $fileSize; //文件大小
    private $fileType; //文件类型
    private $stateInfo; //上传状态信息,
    private $stateMap = [//上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确",
        "INVALID_URL" => "非法 URL",
        "INVALID_IP" => "非法 IP"
    ];
    private $file_config;
    private $is_small = False;
    private $smallPath;

    public function beforeAction($action) {
        $action->controller->enableCsrfValidation = false;
        return true;
    }

    /**
     * 上传
     * @param type $action
     */
    function actionIndex($action) {
        switch ($action) {
            //配置信息
            case 'config':
                return $this->getConfig();
            /* 上传图片 */
            case 'uploadimage':
                $this->getConfig();
                $this->uploadImage();
                return $this->getFileInfo();
            case 'uploadwxfile':
                $this->getConfig();
                $this->upWxFile();
                return $this->getFileInfo();
        }
        return $this->errorJson();
    }

    /**
     * 获取配置信息
     */
    private function getConfig() {
        $this->config["imageActionName"] = "uploadimage"; /* 执行上传图片的action名称 */
        $this->config["imageFieldName"] = "upfile"; /* 提交的图片表单名称 */
        $this->config["imageMaxSize"] = 1 * 1024 * 1024; /* 上传大小限制，单位B */
        $this->config["imageAllowFiles"] = [".png", ".jpg", ".jpeg", ".gif", ".bmp"]; /* 上传图片格式显示 */
        $this->config["imageCompressEnable"] = false; /* 是否压缩图片,默认是true */
        $this->config["imageCompressBorder"] = 1600; /* 图片压缩最长边限制 */
        $this->config["imageInsertAlign"] = "none"; /* 插入的图片浮动方式 */
        $this->config["imageUrlPrefix"] = ""; /* 图片访问路径前缀 */
        $this->config["imagePathFormat"] = "/up/{yyyy}/{mm}/{dd}/{time}{rand:6}"; /* 上传保存路径,可以自定义保存路径和文件名格式 */
        return $this->config;
    }

    /**
     * 上传图片
     */
    private function uploadImage() {
        $post = Yii::$app->request->post();
        $this->fileField = $this->config["imageFieldName"];
        $this->file_config = [
            "pathFormat" => $this->config["imagePathFormat"],
            "maxSize" => $this->config["imageMaxSize"],
            "allowFiles" => $this->config["imageAllowFiles"],
        ];
        if (isset($post['is_small']) && $post['is_small'] == 1)
            $this->is_small = TRUE;
        $this->upFile();
    }

    /**
     * 上传文件的主处理方法
     * @return mixed
     */
    private function upFile() {
        $this->web_path = Yii::getAlias('@frontend/web');
        $this->web_res_path = $this->web_path;
        $doamin_url = Yii::$app->params['WEB_URL'];
        Yii::info($this->fileField);
        $file = $this->file = $_FILES[$this->fileField];
        if (!$file) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getStateInfo($file['error']);
            return;
        } else if (!file_exists($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMP_FILE_NOT_FOUND");
            return;
        } else if (!is_uploaded_file($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMPFILE");
            return;
        }
        $this->oriName = $file['name'];
        $this->fileSize = $file['size'];
        $this->fileType = $this->getFileExt();
        $new_name = $this->getFullName();
        $this->fileNewSmallName = $this->smallPath;
        $this->smallFullName = $doamin_url . $this->smallPath;
        $this->smallPath = $this->web_res_path . $this->smallPath;
        $this->fileNewName = $new_name;
        $this->fullName = $doamin_url . $new_name;

        $this->filePath = $this->web_res_path . $new_name;
        $this->fileName = $this->getFileName();
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(move_uploaded_file($file["tmp_name"], $this->filePath) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
        } else { //移动成功
            if ($this->is_small) {
                try {
                    Image::thumbnail($this->filePath, 640, 480)->save($this->smallPath, ['quality' => 80]);
                } catch (Exception $e) {
                    
                }
            }
            $size = getimagesize($this->filePath);
            $width = $size[0];
            if ($width > 640) {
                $height = $size[1];
                $height = ceil((640 / $width) * $height);
                Image::thumbnail($this->filePath, 640, $height)->save($this->filePath, ['quality' => 80]);
            }
            $this->stateInfo = $this->stateMap[0];
        }
    }

    /**
     * 上传文件的主处理方法
     * @return mixed
     */
    private function upWxFile() {
        $this->fileField = $this->config["imageFieldName"];
        $this->file_config = [
            "pathFormat" => '',
            "maxSize" => 2 * 1024 * 1024,
            "allowFiles" => [".txt"],
        ];
        $this->web_path = Yii::getAlias("@frontend/web");
        $this->web_res_path = $this->web_path . '/wx/';
        $file = $this->file = $_FILES[$this->fileField];
        if (!$file) {
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_NOT_FOUND");
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getStateInfo($file['error']);
            return;
        } else if (!file_exists($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMP_FILE_NOT_FOUND");
            return;
        } else if (!is_uploaded_file($file['tmp_name'])) {
            $this->stateInfo = $this->getStateInfo("ERROR_TMPFILE");
            return;
        }

        $this->oriName = $file['name'];
        $this->fileSize = $file['size'];
        $this->fileType = $this->getFileExt();
        $this->fileNewName = $file['name'];
        $this->fullName = $file['name'];
        $this->filePath = $this->web_res_path . $file['name'];
        $this->fileName = $file['name'];
        $dirname = dirname($this->filePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo("ERROR_SIZE_EXCEED");
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getStateInfo("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getStateInfo("ERROR_DIR_NOT_WRITEABLE");
            return;
        }
//        //移动文件
        if (!(move_uploaded_file($file["tmp_name"], $this->filePath) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
        }
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName() {
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        $format = $this->file_config['pathFormat'];
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);

        //替换随机字符串
        $randNum = rand(1, 100000000) . rand(1, 100000000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }
        $ext = $this->getFileExt();
        $this->smallPath = $format . '_m' . $ext;
        return $format . $ext;
    }

    /**
     * 获取文件名
     * @return string
     */
    private function getFileName() {
        return substr($this->filePath, strrpos($this->filePath, '/') + 1);
    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo($errCode) {
        return !$this->stateMap[$errCode] ? $this->stateMap["ERROR_UNKNOWN"] : $this->stateMap[$errCode];
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private function checkSize() {
        return $this->fileSize <= ($this->file_config['maxSize']);
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType() {
        return in_array($this->getFileExt(), $this->file_config['allowFiles']);
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt() {
        return strtolower(strrchr($this->oriName, '.'));
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo() {
        return array(
            "state" => $this->stateInfo,
            "url" => $this->fullName,
            "title" => $this->fileName,
            "name" => $this->fileNewName,
            "small_name" => $this->fileNewSmallName,
            "original" => $this->oriName,
            "type" => $this->fileType,
            "size" => $this->fileSize,
            'small_url' => $this->smallFullName
        );
    }

}
