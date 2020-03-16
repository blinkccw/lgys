<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business".
 *
 * @property int $id
 * @property int $vip_id 用户
 * @property int $sort_id
 * @property int $grade_id 等级
 * @property string $name 名称
 * @property string $contacts 联系人
 * @property string $phone 手机号
 * @property string $tel 电话
 * @property string $address 地址
 * @property string $license_path 营业执照
 * @property string $face_path 头像
 * @property string $longitude 经度
 * @property string $latitude 纬度
 * @property string $hours 营业时间	
 * @property double $points 剩余代币
 * @property double $total_points 总代币
 * @property int $exchange_pre 发行比例
 * @property int $deduction_pre 抵扣比例
 * @property double $per 人均
 * @property double $exchange_points 发行量
 * @property double $deduction_points 承销量
 * @property int $status 状态
 * @property int $is_audit 审核状态
 * @property int $is_hot 是否推荐
 * @property string $mch_id 微信商户号
 * @property string $created_at 创建日期
 */
class Business extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vip_id', 'name', 'phone', 'address'], 'required'],
            [['vip_id', 'sort_id', 'grade_id', 'exchange_pre', 'deduction_pre', 'status', 'is_audit', 'is_hot'], 'integer'],
            [['points', 'total_points', 'per', 'exchange_points', 'deduction_points'], 'number'],
            [['created_at'], 'safe'],
            [['name', 'mch_id'], 'string', 'max' => 100],
            [['contacts', 'tel', 'license_path', 'face_path', 'longitude', 'latitude', 'hours'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 11],
            [['address'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_id' => '用户',
            'sort_id' => 'Sort ID',
            'grade_id' => '等级',
            'name' => '名称',
            'contacts' => '联系人',
            'phone' => '手机号',
            'tel' => '电话',
            'address' => '地址',
            'license_path' => '营业执照',
            'face_path' => '头像',
            'longitude' => '经度',
            'latitude' => '纬度',
            'hours' => '营业时间	',
            'points' => '剩余代币',
            'total_points' => '总代币',
            'exchange_pre' => '发行比例',
            'deduction_pre' => '抵扣比例',
            'per' => '人均',
            'exchange_points' => '发行量',
            'deduction_points' => '承销量',
            'status' => '状态',
            'is_audit' => '审核状态',
            'is_hot' => '是否推荐',
            'mch_id' => '微信商户号',
            'created_at' => '创建日期',
        ];
    }
    
    
    /**
     * 关联分类
     * @return type
     */
    public function getSort() {
        return $this->hasOne(BusinessSort::className(), ['id' => 'sort_id']);
    }

    /**
     * 关联等级
     * @return type
     */
    public function getGrade() {
        return $this->hasOne(BusinessGrade::className(), ['id' => 'grade_id']);
    }

    /**
     * 关联用户
     * @return type
     */
    public function getVip() {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id']);
    }

    /**
     * 关联图片
     * @return type
     */
    public function getImgs() {
        return $this->hasMany(BusinessImg::className(), ['business_id' => 'id'])->orderBy('order_num,id');
    }

    /**
     * 封面
     */
    public function getFace() {
        return $this->hasOne(BusinessImg::className(), ['business_id' => 'id'])->where(['type' => 1])->orderBy('order_num,id');
    }

    /**
     * 菜品图片
     * @return type
     */
    public function getFoodImgs() {
        return $this->hasMany(BusinessImg::className(), ['business_id' => 'id'])->where(['type' => 2])->orderBy('order_num,id');
    }

    /**
     * 环境图片
     * @return type
     */
    public function getShopImgs() {
        return $this->hasMany(BusinessImg::className(), ['business_id' => 'id'])->where(['type' => 1])->orderBy('order_num,id');
    }

    /**
     * 活动
     */
    public function getActivitys() {
        return $this->hasMany(BusinessActivity::className(), ['business_id' => 'id'])->where(['>=', 'end_at', date('Y-m-d')])->orderBy('id desc');
    }

    public function getLastAlliance() {
        return $this->hasOne(AllianceBusiness::className(), ['business_id' => 'id'])->where(['status' => 1])->orderBy('id desc');
    }

    /**
     * 材料
     */
    public function getMaterial() {
        return $this->hasOne(BusinessMaterial::className(), ['business_id' => 'id']);
    }
}
