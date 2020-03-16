<?php

namespace frontend\modules\vip\controllers;

use yii;
use frontend\controllers\BaseVipController;
use backend\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\imagine\Image;

//use node\lib\HostresFunctions;

/**
 * 上传操作
 * Site controller
 */
class UploadController extends BaseVipController {

    public $enableCsrfValidation = false;
    private $code = 0;
    private $extensions;
    private $stateInfo;
    private $fullName;
    private $fileName;
    private $oriName;
    private $fileType;
    private $fileSize;
    private $resourceId;
    private $stateMap = array(//上传状态映射表，国际化用户需考虑此处数据的国际化
        0 => "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        1 => "发送请求方的IP地址没有被运行访问",
        2 => "请求的方式有问题",
        3 => "传入的数据有有些不能为空的数据为空",
        4 => "站点端生成文件路径错误",
        5 => "参数没能正确传入，确保参数 site_id,key,type 这三个全部存在，缺一不可",
        6 => "文件大小超出了传输的范围或者文件的大小超出了限制的范围",
        7 => "传入的 site_id 未能从数据库中找到对应的数据，也就是说 site_id 不存在",
        11 => "上传文件至的路径不能为空",
        12 => "验证文件上传的 error code 不为 0 报错",
        13 => "上传文件后缀不被允许,允许的后缀",
        14 => "上传文件类型不被允许,允许的类型",
        15 => "不是上传过来得到的文件",
        110 => "非法操作，可能:传入的Host ID 在站点端未能找到或者用户名跟安全码不对",
        500 => "服务器内部错误",
        20 => "type参数错误",
        30 => '图片资源达到上限',
        101 => '网站空间达到上限',
    );
    private $resType = [
        'upImage' => 0,
        'upFlash' => 1,
        'upFile' => 2,
        'upVideo' => 3,
        'upCompressed' => 2,
        'upWxfile' => 4,
        'other' => 5,
    ];
    private $typeArr = [
        'image' => ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'ico', 'image/jpg', 'image/jpeg', 'image/png'],
        'file' => ['pdf', 'xla', 'xls', 'xlsx', 'xlt', 'xlw', 'doc', 'docx', 'txt', 'zip', 'rar'],
        'flash' => ['swf'],
        'video' => ['mp4'],
    ];
    private $smallPath;

    public function verbs() {
        return ['*' => ['post']];
    }

    public function actionUploadimage() {
        $this->extensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'ico'];
        return $this->upload('upImage');
    }

    public function actionUploadwxfile() {
        $this->extensions = ['txt'];
        return $this->upload('upWxfile');
    }

    public function actionUploadfile() {
        $this->extensions = ['pdf', 'xla', 'xls', 'xlsx', 'xlt', 'xlw', 'doc', 'docx', 'rar', 'zip', 'txt'];
        return $this->upload('upFile');
    }

    public function actionUploadcompressed() {
        $this->extensions = ['zip', 'rar'];
        return $this->upload('upCompressed');
    }

    public function actionUploadflash() {
        $this->extensions = ['swf'];
        return $this->upload('upFlash');
    }

    public function actionUploadvideo() {
        $this->extensions = ['mp4'];
        return $this->upload('upVideo');
    }

    public function actionUploadall() {
        $typeAll = $this->typeArr;
        $name = mb_strtolower(Yii::$app->request->post('name'), 'utf-8');
        $type = substr(strrchr($name, '.'), 1);

        if (in_array($type, $typeAll['image'])) {
            $this->extensions = $typeAll['image'];
            return $this->upload('upImage');
        }
        if (in_array($type, $typeAll['file'])) {
            $this->extensions = $typeAll['file'];
            return $this->upload('upFile');
        }
        if (in_array($type, $typeAll['flash'])) {
            $this->extensions = $typeAll['flash'];
            return $this->upload('upFlash');
        }
        if (in_array($type, $typeAll['video'])) {
            $this->extensions = $typeAll['video'];
            return $this->upload('upVideo');
        }
    }

    private function upload($class) {

//        $magicFile = Yii::getAlias(null);
//        finfo_open(FILEINFO_MIME_TYPE, $magicFile);
//        die('{"state":"SUCCESS","id":302,"url":"\/htdocs\/62\/57\/resource\/image\/jpeg\/5642ea9f2b627.jpg","title":"5642ea9f2b627.jpg","original":"44.jpg","type":".jpg","size":8594}');
        //  $hostres = $this->hostres;
//        // 验证空间大小
//        if(!HostresFunctions::checkSpace($hostres)){
//            $this->stateInfo = $this->getStateInfo(40);
//            $this->returnAjax();
//        }
        // 验证图片数量
//        if ($class == 'upImage' && !HostresFunctions::checkPics($hostres)) {
//            $this->stateInfo = $this->getStateInfo(30);
//            return $this->returnAjax();
//        }

        if (Yii::$app->request->isPost) {
            $up_dir = Yii::getAlias('@frontend/web');
            $model = new UploadForm();
            $model->$class = UploadedFile::getInstanceByName('file');
            $file = $model->$class;
            $this->fileType = $file->extension;
            $file_name = '/up/' . $this->getFullName($file);
            $this->fullName = $file_name . '.' . $file->extension;
            $small_file_name = '/up/' . $this->smallPath;
            $path = $up_dir . $file_name . '.' . $file->extension;
            $this->smallPath = $up_dir . $small_file_name;
            $request = Yii::$app->request;
//            if (!$type = $request->post('type')) {
//                $this->stateInfo = $this->getStateInfo(20);
//                return $this->returnAjax();
//            }
//            Yii::warning($model->toArray());
            if (!$this->validateExtension($file)) {
                $this->stateInfo = $this->getStateInfo(14);
                return $this->returnAjax();
            }
            if (!$model->validate()) {
                $this->stateInfo = '上传文件大小过大。';
                return $this->returnAjax();
            }
            $dirname = dirname($path);

            //创建目录失败
            if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
                $this->stateInfo = '创建文件夹失败。';
                return $this->returnAjax();
            } else if (!is_writeable($dirname)) {
                $this->stateInfo = '没有文件写入权限。';
                return $this->returnAjax();
            }
            if (!(move_uploaded_file($file->tempName, $path) && file_exists($path))) { //移动失败
                $this->stateInfo = '上传失败。';
            } else {
//                if ($class == 'upImage') {
//                    try {
//                        Image::thumbnail($path, 640, 480)->save($this->smallPath, ['quality' => 80]);
//                    } catch (Exception $e) {
//                        
//                    }
//                }

                $size = getimagesize($path);
                $width = $size[0];
                if ($width > 640) {
                    $height = $size[1];
                    $height = ceil((640 / $width) * $height);
                    Image::thumbnail($path, 640, $height)->save($path, ['quality' => 80]);
                }
            }
        } else {
            $this->stateInfo = $this->getStateInfo(2);
        }
        $this->code = 1;
        return $this->returnAjax();
    }

    public function actionConfig() {
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(Yii::getAlias('@webroot') . "/js/lib/ueditor/config.json")), true);
        $CONFIG['imageUrlPrefix'] = 'http://' . Yii::$app->params['web_domain'];
        return $CONFIG;
    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo($errCode) {
        if (is_array($errCode))
            $errCode = $errCode[0];
        return !$this->stateMap[$errCode] ? $this->stateMap["ERROR_UNKNOWN"] : $this->stateMap[$errCode];
    }

    private function returnAjax() {
        return [
            "code" => $this->code,
            "state" => $this->stateInfo,
            'id' => $this->resourceId,
            "url" => $this->fullName,
            "title" => $this->fileName,
            "original" => $this->oriName,
            "type" => $this->fileType,
            "size" => $this->fileSize
        ];
    }

    protected function validateExtension($file) {
        $extension = mb_strtolower($file->extension, 'utf-8');
        Yii::info('fileType');
        Yii::info($file->type);
        $mimeType = $file->type;
        if ($mimeType === null)
            return false;
        $extensionsByMimeType = FileHelper::getExtensionsByMimeType($mimeType);

        Yii::info(\yii\helpers\ArrayHelper::toArray($file));
        Yii::info($_FILES);
        Yii::info($mimeType);

        Yii::info($this->extensions);

//        if (stripos($mimeType, 'application') === 0) {
//            $extensionsByMimeType = array_merge($extensionsByMimeType, ['doc', 'xlt', 'xls', 'zip', 'rar', 'xlw', 'mp4']);
//        }
        Yii::info($extension);
        Yii::info($extensionsByMimeType);
//        if (!in_array($extension, $extensionsByMimeType, true))
//            return false;

        if (!in_array($extension, $this->extensions, true))
            return false;
        return true;
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getFullName($file) {
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        $format = '{yyyy}/{mm}/{dd}/{time}{rand:6}';
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
        $this->smallPath = $format . '_m.' . $this->fileType;
        return $format;
    }

}
