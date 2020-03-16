<?php

namespace backend\modules\setting\controllers;

use backend\controllers\BaseController;
use common\models\Config;
use yii\web\HttpException;

/**
 * 设置页面
 */
class PageController extends BaseController {

    public function actionIndex() {
        $config = Config::findOne(['id' => 1]);
        if (!$config)
            throw new HttpException(404, '配置信息已经不存在。');
        return $this->renderAjax('index',['config'=>$config]);
    }

    /**
     * 管理员页面
     */
    public function actionUser() {
        return $this->renderAjax('user', ['user' => $this->user]);
    }

    /**
     * 管理员列表页面
     * @return type
     */
    public function actionUserList() {
        $model = new \backend\modules\setting\models\user\UserList;
        $model->setAttributes($this->post);
        $rel = $model->getList();
        $rel['user'] = $this->user;
        return $this->renderAjax('user_list', $rel);
    }

    /**
     * 管理员操作表单
     * @return type
     */
    public function actionUserForm() {
        return $this->renderAjax('user_form');
    }

    /**
     * 登录日志页面
     */
    public function actionLoginLog() {
        return $this->renderAjax('login_log');
    }

    /**
     * 登录日志列表页面
     * @return type
     */
    public function actionLoginLogList() {
        $model = new \backend\modules\setting\models\log\LoginLogList;
        $model->setAttributes($this->post);
        return $this->renderAjax('login_log_list', $model->getList());
    }

    /**
     * 密码设置页面
     */
    public function actionEdit() {
        return $this->renderAjax('edit', ['user' => $this->user]);
    }

}
