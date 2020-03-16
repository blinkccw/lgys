<?php

namespace common\core;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Description of BaseAjaxController
 *
 * @author xiaojx
 */
class BaseAjaxController extends Controller {
    const TIMEOUT=404;
    const SUCCESS = 1;
    const FALSE = 0;
    const FORMAT_ERROR='格式不正确。';
    public $page_size = 20;
    /**
     * @inheritdoc
     */
    function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   'captcha'=>['get'],
                   '*' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 初始化
     */
    function init() {
        parent::init(); 
       Yii::$app->response->format = Response::FORMAT_JSON;
    }
    
  
      /**
     * 返回结果
     * @param type $rel
     * @param type $message
     * @return type
     */
    function relJson($rel, $result = '', $message = '') {
        return $this->returnJson($rel?self::SUCCESS:self::FALSE,$result, $message);
    }

    /**
     * 返回结果
     * @param type $code
     * @param type $message
     * @return type
     */
    function returnJson($code, $result = '', $message = '') {
        $data = ['code' => $code?self::SUCCESS:self::FALSE];
        if ($message !== '')
            $data['message'] = $message;
        if ($result !== '')
            $data['result'] = $result;
        return $data;
    }
    
     /**
     * 返回成功信息
     * @param type $error
     * @return type
     */
    function sucJson($result='') {
        $data = ['code' => self::SUCCESS];
        if ($result !== '')
            $data=array_merge($data,$result);
        return $data;
    }
    /**
     * 返回失败信息
     * @param type $model
     * @return type
     */
     function errorModelJson($model=null) {
        return ['code' => self::FALSE,'message'=>$model->hasErrors() ? current(array_values($model->firstErrors)) : ''];
    }
    
    /**
     * 返回失败信息
     * @param type $error
     * @return type
     */
    function errorJson($error='') {
        return ['code' => self::FALSE,'message'=>$error];
    }
    
     /**
     * 返回失败信息
     * @param type $error
     * @return type
     */
    function formatErrorJson() {
        return ['code' => self::FALSE,'message'=>self::FORMAT_ERROR];
    }
    
       /**
     * 分页信息
     * @param type $page_index
     * @param type $counts
     */
    public function getPageData($page_index, $counts) {
        $page['counts'] = $counts;
        $page['pageindex'] = $page_index;
        $page['pagecount'] = ceil($counts / $this->page_size);
        $page['pagesize'] = $this->page_size;
        return $page;
    }

}
