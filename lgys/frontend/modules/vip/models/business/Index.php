<?php

namespace frontend\modules\vip\models\business;

use Yii;
use common\models\Pay;
use common\models\BusinessPoints;
use yii\base\Model;

/**
 * 商户首页
 *
 * @author xjx
 */
class Index extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            ['id', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '商户ID'
        ];
    }

    /**
     * 获取首页信息
     */
    public function index($vip_id) {
        $data_page=Pay::find()->where(['business_id' => $this->id, 'status' => 1])->andWhere(['>', 'created_at', date('Y-m-d')]);
        $data['pay']['all_pay'] = $data_page->sum('pay');
        if ($data['pay']['all_pay'] == null)
            $data['pay']['all_pay'] = 0;
        $data['pay']['all_count'] =$data_page->count('id');
        $data['pay']['all_man_count'] = $data_page->count('distinct vip_id');
        $data['pay']['ercentage'] =BusinessPoints::find()->where(['business_id' => $this->id, 'points_type' => 2,'flag'=>1])->sum('points');
         if ($data['pay']['ercentage'] == null)
            $data['pay']['ercentage'] = 0;
        $data['pay_list'] = [];
        $pay_list = $data_page
                ->with(['vip'])
                ->orderBy('id desc')
                ->limit(10)
                ->all();
        foreach ($pay_list as $log) {
            $item['id'] = $log->id;
            $item['vip_name'] = $log->vip ? $log->vip->name : '无';
            $item['money'] = $log->money;
            $item['time'] = $this->getTimeName($log->created_at);
            $data['pay_list'][] = $item;
        }
        return $data;
    }

    private function getTimeName($at) {
        $t = time() - strtotime($at);
        if ($t < 60) {
            return $t . '秒前';
        }
        if ($t < 60 * 60) {
            return ceil($t / 60) . '分钟前';
        }
        return ceil($t / (60 * 60)) . '小时前';
    }

}
