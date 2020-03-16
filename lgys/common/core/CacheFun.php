<?php

namespace common\core;

use Yii;
use common\models\WxTemplate;
use common\models\UserOperateType;

/**
 * 缓存方法
 */
class CacheFun {

    private static $_instance = null;

    public static function run() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * 功能列表
     */
    function getUserOperateType() {
        $operates = null;
        Yii::$app->cache->delete('m_user_operate_type');
        if (Yii::$app->cache->exists('m_user_operate_type') == 1) {
            $operates = json_decode(Yii::$app->cache->get('m_user_operate_type'), TRUE);
        }
        $operates = null;
        if (!$operates) {
            $operates = UserOperateType::find()->orderBy('parent_id,order_num,id')->asArray()->all();
            Yii::$app->cache->set('m_user_operate_type', json_encode($operates));
        }
        return $operates;
    }

    /**
     * 缓存微信AccessToken
     * @param type $user
     * @return type
     */
    function setWxAccessToken($access_token) {
        return Yii::$app->cache->set('m_wx_token', $access_token);
    }

    /**
     * 获取缓存微信AccessToken
     * @param type $user
     * @return type
     */
    function getWxAccessToken() {
        if (Yii::$app->cache->exists('m_wx_token') == 1)
            return json_decode(Yii::$app->cache->get('m_wx_token'), true);
        return null;
    }

    function getWxTemplatesType($id) {
        $templates = $this->getWxTemplates();
        foreach ($templates as $template) {
            if ($template['id'] == $id)
                return $template;
        }
        return null;
    }

    /**
     * 获取所有微信消息模板
     */
    function getWxTemplates() {
        $templates = null;
        //  $this->clearWxTemplates();
        if (Yii::$app->cache->exists('m_wx_template') == 1) {
            $templates = json_decode(Yii::$app->cache->get('m_wx_template'), TRUE);
        }
        if (empty($templates)) {
            $templates = WxTemplate::find()->asArray()->all();
            $this->setWxTemplates($templates);
        }
        return $templates;
    }

    /**
     * 设置所有微信消息模板
     * @param type $user
     * @return type
     */
    function setWxTemplates($templates) {
        return Yii::$app->cache->set('m_wx_template', json_encode($templates));
    }

    /**
     * 清除所有微信消息模板
     */
    function clearWxTemplates() {
        return Yii::$app->cache->delete('m_wx_template');
    }

    /**
     * 获取分销配置
     */
    function getDistributorConfig() {
        $config = null;
        if (Yii::$app->cache->exists('m_distributor_config') == 1) {
            $config = json_decode(Yii::$app->cache->get('m_distributor_config'), TRUE);
        }
        if (!$config) {
            $config = \common\models\DistributorConfig::find()->asArray()->one();
            $this->setDistributorConfig($config);
        }
        return $config;
    }

    /**
     * 设置 分销配置
     * @param type $user
     * @return type
     */
    function setDistributorConfig($config) {
        return Yii::$app->cache->set('m_distributor_config', json_encode($config));
    }

    /**
     * 清除分销配置
     */
    function clearDistributorConfig() {
        return Yii::$app->cache->delete('m_distributor_config');
    }

    /**
     * 登录二维码
     */
    function getLoginAuth($code) {
        if ($this->checkLoginAuth($code))
            return Yii::$app->cache->get('m_login:' . $code);
        return -1;
    }

    /**
     * 登录二维码
     */
    function setLoginAuth($code, $val) {
        return Yii::$app->cache->set('m_login:' . $code, $val, 3600);
    }

    /**
     * 是否存在
     * @param type $code
     */
    function checkLoginAuth($code) {
        return Yii::$app->cache->exists('m_login:' . $code) == 1;
    }

    /**
     * 设置微信订单支付
     * @param type $order_id
     */
    function setWxOrder($no, $val) {
        return Yii::$app->cache->set('m_wx_order:' . $no, $val, 3600);
    }

    /**
     * 微信支付处理
     */
    function getWxOrder($no) {
        if ($this->checkWxOrder($no))
            return Yii::$app->cache->get('m_wx_order:' . $no);
        return -1;
    }

    /**
     * 微信支付处理
     * @param type $code
     */
    function checkWxOrder($no) {
        return Yii::$app->cache->exists('m_wx_order:' . $no) == 1;
    }

    /**
     * 微信支付处理
     * @param type $code
     */
    function delWxOrder($no) {
        return Yii::$app->cache->delete('m_wx_order:' . $no);
    }

    /**
     * 设置微信订单支付
     * @param type $order_id
     */
    function setWxGOrder($no, $val) {
        return Yii::$app->cache->set('m_wx_g_order:' . $no, $val, 3600);
    }

    /**
     * 微信支付处理
     */
    function getWxGOrder($no) {
        if ($this->checkWxGOrder($no))
            return Yii::$app->cache->get('m_wx_g_order:' . $no);
        return -1;
    }

    /**
     * 微信支付处理
     * @param type $code
     */
    function checkWxGOrder($no) {
        return Yii::$app->cache->exists('m_wx_g_order:' . $no) == 1;
    }

    /**
     * 微信支付处理
     * @param type $code
     */
    function delWxGOrder($no) {
        return Yii::$app->cache->delete('m_wx_g_order:' . $no);
    }

    /**
     * 排队免单(进队列)
     * @param type $call_group_id
     */
    function pulshFree($id) {
        return Yii::$app->redis->rpush('m_free', $id);
    }

    /**
     * 排队免单(出队列)
     * @param type $call_group_id
     */
    function popFree() {
        return Yii::$app->redis->lpop('m_free');
    }

}
