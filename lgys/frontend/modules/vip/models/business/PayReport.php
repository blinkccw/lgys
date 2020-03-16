<?php

namespace frontend\modules\vip\models\business;

use Yii;
use common\models\Pay;
use yii\base\Model;

/**
 * 获取消费报表
 *
 * @author xjx
 */
class PayReport extends Model {

    public $id;
    public $month;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'integer'],
            ['month', 'trim'],
            ['month', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '商户ID',
            'month' => '月份'
        ];
    }

    /**
     * 获取信息
     */
    public function getList() {
        //  $data['business_id'] = $this->id;
        //  $data['status'] = 1;
        if ($this->month == '' || $this->month == null) {
            $this->month = date('Y-m-1');
        } else {
            $this->month = $this->month . '-1';
        }
        $begin_month = $this->month;
        $end_month = date('Y-m-1', strtotime('+1 month', strtotime($begin_month)));
        $rel['list'] = Pay::payReport($this->id, $begin_month, $end_month);
        $rel['month'] = date('Y-m', strtotime($begin_month));
        $rel['pay']['points'] = 0;
        $rel['pay']['money'] = 0;
        foreach ($rel['list'] as $item) {
            $rel['pay']['points'] += $item['all_point'];
            $rel['pay']['money'] += $item['all_pay'];
        }
        $rel['pay']['money'] = sprintf("%.2f", $rel['pay']['money']);
        return $rel;
    }

}
