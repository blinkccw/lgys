<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\widgets;

use Yii;

class Script {

    /**
     * 获取页面js
     * @param type $page
     */
    public static function registerJsFile() {
        $module = Yii::$app->controller->module->id;
        $action = str_replace('-','_',Yii::$app->controller->action->id);
        $path="/js/page/{$module}/{$action}.js";
        $v=empty(Yii::$app->params['jsVersion'])?time():Yii::$app->params['jsVersion'];
        if (is_readable(Yii::getAlias("@backend/web/{$path}"))) {
            return "<script type='text/javascript' src='{$path}?{$v}'></script>";
        }
        return ;
    }

}
