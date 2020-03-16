<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MainAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/iconfont.css',
        '/js/plugins/webupload/webuploader.css',
        '/js/plugins/dhtmlxcolorpicker/css/dhtmlxcolorpicker.css',
        '/css/style.css?1011',
        '/js/plugins/JSCal2/css/jscal2.css'
    ];
    public $js = [
        'https://map.qq.com/api/js?v=2.exp&key=MGNBZ-IOPR6-FUYSD-MMMUF-Z5A42-4NBKZ',
        '/js/plugins/jquery-3.1.1.min.js',
        '/js/plugins/JSCal2/js/jscal2.js',
        '/js/plugins/webupload/webuploader.min.js',
        '/js/plugins/ueditor/ueditor.config.js?0926',
        '/js/plugins/ueditor/ueditor.all.js?0926',
        '/js/plugins/jquery.validate.min.js',
        '/js/plugins/jquery.page.js',
         '/js/plugins/jquery.slimscroll.min.js',
        '/js/plugins/dhtmlxcolorpicker/dhtmlxcolorpicker.min.js',
        '/js/common.js?1106',
        '/js/main.js?1009'
    ];
    public $depends = [
            //    'yii\web\YiiAsset'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

}
