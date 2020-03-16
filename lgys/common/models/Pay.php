<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pay".
 *
 * @property int $id
 * @property string $no 支付订单号
 * @property int $vip_id
 * @property int $business_id 商家ID
 * @property string $business_name
 * @property int $alliance_id 联盟ID
 * @property string $alliance_name
 * @property double $money 总金额
 * @property double $pay 实际支付金额
 * @property double $point 代币获取量
 * @property double $used_point 使用代币数量
 * @property int $status 状态
 * @property string $transaction_id 微信交易码
 * @property string $term
 * @property string $pay_at 支付日期
 * @property string $created_at 创建日期
 */
class Pay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no', 'vip_id', 'business_id'], 'required'],
            [['vip_id', 'business_id', 'alliance_id', 'status','is_do','business_grade_id'], 'integer'],
            [['money', 'pay', 'point', 'used_point'], 'number'],
            [['pay_at', 'created_at'], 'safe'],
            [['no'], 'string', 'max' => 50],
            [['business_name', 'alliance_name'], 'string', 'max' => 100],
            [['transaction_id'], 'string', 'max' => 64],
            [['term'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no' => '支付订单号',
            'vip_id' => 'Vip ID',
            'business_id' => '商家ID',
            'business_name' => 'Business Name',
            'alliance_id' => '联盟ID',
            'business_grade_id'=>'',
            'alliance_name' => 'Alliance Name',
            'money' => '总金额',
            'pay' => '实际支付金额',
            'point' => '代币获取量',
            'used_point' => '使用代币数量',
            'status' => '状态',
            'transaction_id' => '微信交易码',
            'term' => 'Term',
            'is_do'=>'操作状态',
            'pay_at' => '支付日期',
            'created_at' => '创建日期',
        ];
    }
    
    
    /**
     * 关联会员
     * @return type
     */
    public function getVip() {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id'])->select('id,avatar_url,name,vip_no,nick_name');
    }

    /**
     * 关联商户
     * @return type
     */
    public function getBusiness() {
        return $this->hasOne(Business::className(), ['id' => 'business_id']);
    }

    /**
     * 关联联盟
     * @return type
     */
    public function getAlliance() {
        return $this->hasOne(Alliance::className(), ['id' => 'alliance_id']);
    }

      public static function payReport($id, $begin_at, $end_at) {
        $db = Yii::$app->db;
        $parms = [':business_id' => $id, ':begin_at' => $begin_at, ':end_at' => $end_at];
        $sql = "SELECT DATE_FORMAT(created_at,'%Y-%m-%d') days,count(id) pay_counts,sum(pay) all_pay,sum(used_point) all_point,sum(money) all_money FROM pay WHERE business_id=:business_id and status=1 and created_at BETWEEN :begin_at AND :end_at GROUP BY `days` order by `days` desc;";
        return $db->createCommand($sql, $parms)->queryAll();
    }
}
