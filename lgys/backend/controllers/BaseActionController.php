<?php

namespace backend\controllers;

use Yii;
use common\core\BaseAjaxController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\extend\phpexcel\MExcel;

/**
 * Description of BaseAjaxController
 *
 * @author xiaojx
 */
class BaseActionController extends BaseAjaxController {

    public $admin;
    public $admin_id;
    public $post;
    public $page_url = '';

    /**
     * @inheritdoc
     */
    function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 初始化
     */
    function init() {
        parent::init();
        if (!Yii::$app->user->isGuest) {
            $this->admin = Yii::$app->user->identity;
            $this->admin_id = $this->admin->id;
        }
        $this->post = Yii::$app->request->post();
        if (isset($this->post['_url'])) {
            $this->page_url = $this->post['_url'];
        }
    }

    /**
     * 获取文件名
     * @param type $name
     * @return type
     */
    public function saveExcel($file_name, $data) {
        try {
            MExcel::getInstance()->saveSheet(Yii::getAlias('@backend/web/excel/' . $file_name), $data);
        } catch (Exception $e) {
            return '';
        }
        return $file_name;
    }

}
