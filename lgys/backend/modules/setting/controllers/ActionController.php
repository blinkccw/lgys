<?php

namespace backend\modules\setting\controllers;

use yii;
use backend\controllers\BaseActionController;

/**
 * 管理员请求控制器
 */
class ActionController extends BaseActionController {

    /**
     * 管理员操作
     */
    public function actionUserForm() {
        $model = new \backend\modules\setting\models\user\UserForm;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 管理员状态
     */
    public function actionUserStatus() {
        $model = new \backend\modules\setting\models\user\UserStatus;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save($this->user))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 管理员删除
     */
    public function actionUseerDelete() {
        $model = new \backend\modules\setting\models\user\UserDelete;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->delete($this->user))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 操作
     */
    public function actionEditForm() {
        $model = new \backend\modules\setting\models\setting\EditFrom;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save($this->user))
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

    /**
     * 操作
     */
    public function actionConfigForm() {
        $model = new \backend\modules\setting\models\setting\ConfigFrom;
        $model->setAttributes($this->post);
        if (!$model->validate() || !$model->save())
            return $this->errorModelJson($model);
        return $this->sucJson();
    }

}
