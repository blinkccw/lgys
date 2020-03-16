<?php

namespace common\core;

use Yii;
use common\core\CacheFun;
use yii\imagine\Image;

/**
 * 
 */
class WxBusiness {

    private $store_name = '';

    /**
     * 获取微信AccessToken
     */
    public function getAccessToken() {
        $data = CacheFun::run()->getWxAccessToken();
        if (is_null($data) || $data['expire_time'] <= time()) {
            //获取access_token
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . Yii::$app->params['wx_appid'] . "&secret=" . Yii::$app->params['wx_appsecret'];
            $curl = new Curl();
            $response = $curl->get($url);
            if ($curl->responseCode == 200) {
                $response = json_decode($response, TRUE);
                $access_token = isset($response['access_token']) ? $response['access_token'] : "";
                if ($access_token) {
                    $data['expire_time'] = time() + $response['expires_in'] - 10 * 1000;
                    $data['access_token'] = $access_token;
                    CacheFun::run()->setWxAccessToken(json_encode($data));
                } else {
                    return null;
                }
            }
        }
        return $data['access_token'];
    }

    /**
     * 获取二维码
     * @param type $access_token
     */
    public function createQr($img_url, $scene, $page) {
        $access_token = $this->getAccessToken();
        if (is_null($access_token)) {
            return null;
        }
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token;
        $curl = new Curl();
        $param = '{"scene": "' . $scene . '","page": "' . $page . '"}';
        $response = $curl->setOption(CURLOPT_POSTFIELDS, $param)->post($url);
        if ($curl->responseCode == 200) {
            if (strpos($response, "errcode") !== false) {
                Yii::info($response);
                return null;
            } else {
                file_put_contents($img_url, $response);
                return $url;
            }
        } else {
            Yii::error($response);
            return null;
        }
    }

    /**
     * 发送模板消息
     * @param type $access_token
     * @param type $json
     */
    public function sendTemplate($access_token, $json) {
        try {
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
            $curl = new Curl();
            $response = $curl->setOption(CURLOPT_POSTFIELDS, json_encode($json))->post($url);
            Yii::info($response);
            if ($curl->responseCode == 200) {
                return json_decode($response, TRUE);
            } else {
                return null;
            }
        } catch (Exception $e) {
            
        }
        return null;
    }

    /**
     * 发送拼单成功信息
     * @param type $openid
     */
    public function sendCollageSucMsg($openid, $collage, $collage_order) {
        if (empty($openid))
            return;
        $template = CacheFun::run()->getWxTemplatesType(3);
        if (!$template)
            return;
        $access_token = $this->getAccessToken();
        if (is_null($access_token))
            return;
        try {
            $json["touser"] = $openid;
            $json["template_id"] = $template['no'];
            $json["data"]["keyword1"]["value"] = $this->store_name;
            $json["data"]["keyword1"]["color"] = "#000000";
            $json["data"]["keyword2"]["value"] = $collage->goods->name;
            $json["data"]["keyword2"]["color"] = "#000000";
            $json["data"]["keyword3"]["value"] = '￥' . $collage->price;
            $json["data"]["keyword3"]["color"] = "#000000";
            $json["data"]["keyword4"]["value"] = $collage_order->vip->name;
            $json["data"]["keyword4"]["color"] = "#000000";
            $json["data"]["keyword5"]["value"] = '￥' . $collage->price;
            $json["data"]["keyword5"]["color"] = "#000000";
            $json["data"]["keyword6"]["value"] = '拼团成功';
            $json["data"]["keyword6"]["color"] = "#000000";
            $json["data"]["keyword7"]["value"] = $collage_order->completed_at;
            $json["data"]["keyword7"]["color"] = "#000000";
            $this->sendTemplate($access_token, $json);
        } catch (Exception $ex) {
            
        }
    }

}
