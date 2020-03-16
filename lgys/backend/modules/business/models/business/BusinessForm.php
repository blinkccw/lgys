<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\Business;
use common\models\Vip;
use yii\base\Model;

/**
 * 商户表单
 */
class BusinessForm extends Model {

    public $id;
    public $sort_id;
    public $name;
    public $contacts;
    public $phone;
    public $vip_id;
    public $face_path;
    public $longitude;
    public $latitude;
    public $address;
    public $tel;
    public $hours;
    public $mch_id;
    public $exchange_pre;
    public $deduction_pre;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['sort_id'], 'trim'],
            [['sort_id'], 'required'],
            [['sort_id'], 'integer'],
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['exchange_pre'], 'trim'],
            [['exchange_pre'], 'required'],
            [['exchange_pre'], 'integer'],
            [['deduction_pre'], 'trim'],
            [['deduction_pre'], 'required'],
            [['deduction_pre'], 'integer'],
            [['contacts'], 'trim'],
            [['contacts'], 'required'],
            [['contacts'], 'string', 'max' => 50],
            [['phone'], 'trim'],
            [['phone'], 'required'],
            [['phone'], 'string', 'max' => 11],
            [['vip_id'], 'trim'],
            [['vip_id'], 'required'],
            [['vip_id'], 'integer'],
            [['face_path'], 'trim'],
            [['face_path'], 'string', 'max' => 50],
            [['longitude'], 'trim'],
            [['longitude'], 'string', 'max' => 50],
            [['latitude'], 'trim'],
            [['latitude'], 'string', 'max' => 50],
            [['address'], 'trim'],
            [['address'], 'string', 'max' => 200],
            [['tel'], 'trim'],
            [['tel'], 'string', 'max' => 50],
            [['hours'], 'trim'],
            [['hours'], 'string', 'max' => 50],
            [['mch_id'], 'trim'],
            [['mch_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'sort_id' => '商户分类',
            'name' => '名称',
            'exchange_pre' => '发行比例',
            'deduction_pre' => '抵扣比例',
            'contacts' => '联系人',
            'phone' => '手机号',
            'vip_id' => '用户',
            'face_path' => '头像',
            'longitude' => '经度',
            'latitude' => '纬度',
            'address' => '地址',
            'hours' => '营业时间',
            'mch_id' => '微信支付商户号'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $business = new Business;
        if ($this->id > 0) {
            $business = Business::find()->where(['id' => $this->id])->one();
            if (!$business) {
                $this->addError('save', '商户信息不存在。');
                return FALSE;
            }
        }
        $vip = Vip::find()->where(['id' => $this->vip_id])->one();
        if (!$vip) {
            $this->addError('save', '所选择用户信息已经不存在。');
            return FALSE;
        }
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $business->sort_id = $this->sort_id;
            $business->vip_id = $this->vip_id;
            $business->name = $this->name;
            $business->contacts = $this->contacts;
            $business->phone = $this->phone;
            $business->address = $this->address;
            $business->face_path = $this->face_path;
            $business->longitude = $this->longitude;
            $business->latitude = $this->latitude;
            $business->tel = $this->tel;
            $business->hours = $this->hours;
            $business->exchange_pre = $this->exchange_pre;
            $business->deduction_pre = $this->deduction_pre;
            $business->mch_id = $this->mch_id;
            $business->status = 1;
            if (!$business->save())
                return false;

            if ($vip->is_business == 0) {
                $vip->is_business = 1;
                if (!$vip->save())
                    return false;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
