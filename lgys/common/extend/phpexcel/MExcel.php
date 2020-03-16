<?php

namespace common\extend\phpexcel;

require 'PHPExcel.php';

class MExcel extends \PHPExcel {

    public static $instance;

    public static function getInstance() {
        if (empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public static function init() {
        return true;
    }

    /**
     * 获取Excel表单数据
     * @param int $inFile  读取文件路径
     * @param bool $index   读取表格索引，默认读取所有数据 合并后返回
     * @return array
     */
    public function readSheet($inFile, $index = false) {
        $type = \PHPExcel_IOFactory::identify($inFile);
        $reader = \PHPExcel_IOFactory::createReader($type);
        $sheet = $reader->load($inFile);
//        $aIndex = $sheet->getActiveSheetIndex();//获取当前活动表格索引
        $sCount = $sheet->getSheetCount(); //获取文件中表格数量
//        Jeen::echoln($aIndex.' of '.$sCount);
        if (is_int($index) && $index < $sCount && $index >= 0)
            return $sheet->getSheet($index)->toArray();
        if ($sCount == 1)
            return $sheet->getSheet(0)->toArray();
        $data = [];
        for ($i = 0; $i < $sCount; $i++) {
            $data[] = $sheet->getSheet($i)->toArray();
        }
        unset($sheet);
        unset($reader);
        unset($type);
        return $data;
    }

    /**
     * 将数据保存至Excel 表格
     * @param string $outFile 输出文件路径
     * @param array $data  需要保存的数据  二维数组
     * @return bool
     */
    public function saveSheet($outFile, array $data) {
        $path = dirname($outFile);
        if (!file_exists($path)) { //目录不存在 则创建目录 并开放权限
            @mkdir($path, 0777, TRUE);
            @chmod($path, 0777);
        }
//        foreach ($data as $k=>$v){
//             if(strpos($data[$k][$v],'=') === 0){
//               $data[$k][$v]= "'".$data[$k][$v];
//            }
//        }
        for ($i = 0; $i < count($data); $i++) {
            for ($j = 0; $j < count($data[$i]); $j++) {
                if (strpos($data[$i][$j], '=') === 0) {
                    $data[$i][$j] = "'" . $data[$i][$j];
                }
            }
        }
        $newExcel = new \PHPExcel();
        $newSheet = $newExcel->getActiveSheet();
        $newSheet->fromArray($data);
        $objWriter = \PHPExcel_IOFactory::createWriter($newExcel, 'Excel5');
        $objWriter->save($outFile);
        unset($objWriter);
        unset($newSheet);
        unset($newExcel);
        return true;
    }

    /**
     * @param array $data 需要过滤处理的数据 二维数组
     * @param int $cols  取N列
     * @param int $offset  排除 N 行
     * @param bool|int $must 某列不可为空  0 - index
     * @return array
     */
    public function handleSheetArray(array $data, $cols = 10, $offset = 1, $must = false) {
        $final = [];
        if ($must && $must >= $cols)
            $must = false;
        foreach ($data as $k => $row) {
            if ($k < $offset)
                continue;
            $t = [];
            for ($i = 0; $i < $cols; $i++) {
                if (isset($row[$i]))
                    $t[$i] = trim(strval($row[$i]));
                else
                    $t[$i] = '';
            }
            if (is_array($row) && implode('', $t) && ($must === false || $t[$must])) {
                $final[] = $t;
                continue;
            }
        }
        return $final;
    }

}
