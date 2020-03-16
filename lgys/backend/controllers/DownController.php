<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
/**
 * Description of DownController
 *
 * @author xjx
 */
class DownController  extends Controller{
    
    public function actionIndex($file_path) {
        $name = $file_path;
        $dir = Yii::getAlias('@frontend/web/qr/b/');
        $file_path = $dir . $file_path;
        Yii::info($file_path);
        if (file_exists($file_path)) {
            $file = fopen($file_path, "r");
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($file_path));
            Header("Content-Disposition: attachment; filename=" . urldecode($name));
            // 输出文件内容
            echo fread($file, filesize($file_path));
            fclose($file);
        }
    }
}
