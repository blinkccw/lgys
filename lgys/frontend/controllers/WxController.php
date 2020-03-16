<?php

namespace frontend\controllers;

use Yii;
use common\core\BaseAjaxController;
use common\core\Curl;
use yii\filters\VerbFilter;
use common\models\Vip;
use common\core\CommonFun;

/**
 * 微信请求
 */
class WxController extends BaseAjaxController {

    public $enableCsrfValidation = false;
    public $post;

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
        ];
    }

    /**
     * 初始化
     */
    function init() {
        parent::init();
        $this->post = Yii::$app->request->post();
    }

    /**
     * 登陆小程序登陆
     *
     * @return mixed
     */
    public function actionLogin() {
        if (isset($this->post['code'])) {
            $url = 'https://api.weixin.qq.com/sns/jscode2session';
            $appid = Yii::$app->params['wx_appid'];
            $secret = Yii::$app->params['wx_appsecret'];
            $js_code = $this->post['code'];
            $curl = new Curl();
            $response = $curl->get($url . "?appid={$appid}&secret={$secret}&js_code={$js_code}&grant_type=authorization_code");
            if ($curl->responseCode == 200) {
                $response = json_decode($response, TRUE);
                if (isset($response['openid'])) {
                    $vip = Vip::find()->where(['open_id' => $response['openid']])->one();
                    $user_info = [];
                    if (!$vip) {
                        $vip = new Vip;
                        $vip->open_id = $response['openid'];
                        $vip->generateAuthKey();
                        $vip->setPassword("Aa123456");
                        $vip->is_auth = 0;
                        $vip->status = 0;
                        $vip->logined_count = 0;
                        $user_info['is_auth'] = 0;
                        $user_info['status'] = 0;
                        //获取会员号
                        do {
                            $vip->vip_no = CommonFun::createVipNo();
                            $tem = Vip::findOne(['vip_no' => $vip->vip_no]);
                            if (!$tem)
                                break;
                        } while (true);
                    } else {
                        $user_info['status'] = $vip->status;
                        $user_info['is_auth'] = $vip->is_auth;
                        $user_info['id'] = $vip->id;
                        $user_info['avatar_url'] = $vip->avatar_url;
                        $user_info['name'] = $vip->name;
                        $user_info['nick_name'] = $vip->nick_name;
                        $user_info['country'] = $vip->country;
                        $user_info['province'] = $vip->province;
                        $user_info['city'] = $vip->city;
                        $user_info['language'] = $vip->language;
                        if (Yii::$app->cache->exists('vip:' . $vip->id) == 1) {
                            return $this->sucJson(['token' => Yii::$app->cache->get('vip:' . $vip->id), 'userInfo' => $user_info]);
                        }
                    }
                    $vip->logined_count++;
                    $vip->logined_at = date('Y-m-d H:i:s');
                    if (!$vip->save())
                        return $this->errorJson('登陆失败。');
                    $vip_id = $vip->id;
                    $user_info['id'] = $vip->id;
                    $token = Yii::$app->security->generateRandomString();
                    Yii::$app->cache->set('vip:' . $vip_id, $token, 60 * 60 * 48);
                    return $this->sucJson(['token' => $token, 'userInfo' => $user_info]);
                }
            }
        }
        return $this->errorJson();
    }

}
