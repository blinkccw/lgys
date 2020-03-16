<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
   public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/iconfont.css',
        '/css/style.css?1011'
    ];
    public $js = [
        '/js/plugins/jquery-3.1.1.min.js',
        '/js/plugins/jquery.validate.min.js',
        '/js/common.js?1106'
    ];
    public $depends = [
            //    'yii\web\YiiAsset'
    ];
     public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}

