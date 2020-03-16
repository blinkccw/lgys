<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
/**
 * Site controller
 */
class SiteController extends Controller
{
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        throw new HttpException(404, 'Page not found.');
        die();
    }

}
