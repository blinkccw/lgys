<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\Business;
use common\models\BusinessMaterial;
use common\core\Curl;
use yii\base\Model;

/**
 * 商户表单
 */
class BusinessForm extends Model {

    public $id;
    public $sort_id;
    public $name;
    public $license_path;
    public $contacts;
    public $phone;
    public $address;
    public $email;
    public $bank_card;
    public $bank_add;
    public $card_path1;
    public $card_path2;
    public $food_license_path;

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
            [['contacts'], 'trim'],
            [['contacts'], 'required'],
            [['contacts'], 'string', 'max' => 50],
            [['phone'], 'trim'],
            [['phone'], 'required'],
            [['phone'], 'string', 'max' => 11],
            [['license_path'], 'trim'],
            [['license_path'], 'string', 'max' => 50],
            [['address'], 'trim'],
            [['address'], 'string', 'max' => 200],
            [['email'], 'trim'],
            [['email'], 'string', 'max' => 200],
            [['bank_card'], 'trim'],
            [['bank_card'], 'string', 'max' => 100],
            [['bank_add'], 'trim'],
            [['bank_add'], 'string', 'max' => 200],
            [['card_path1'], 'trim'],
            [['card_path1'], 'string', 'max' => 50],
            [['card_path2'], 'trim'],
            [['card_path2'], 'string', 'max' => 50],
            [['food_license_path'], 'trim'],
            [['food_license_path'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'sort_id' => '商户分类',
            'name' => '商户名称',
            'contacts' => '联系人',
            'phone' => '手机号码',
            'license_path' => '营业执照',
            'address' => '商户地址',
            'email' => '电子邮箱',
            'bank_card' => '银行卡号',
            'bank_add' => '开户行地址',
            'card_path1' => '身体证正面',
            'card_path2' => '身体证反面',
            'food_license_path' => '食品许可证'
        ];
    }

    /**
     * 保存
     */
    public function save($vip_id) {
        $business = new Business;
        if ($this->id > 0) {
            $business = Business::find()->where(['id' => $this->id])->one();
            if (!$business) {
                $this->addError('save', '商户信息不存在。');
                return FALSE;
            }
        }
        $longitude = "";
        $latitude = "";
        $url = "https://apis.map.qq.com/ws/geocoder/v1/?key=" . Yii::$app->params['mapKey'] . "&address=" . urlencode($this->address);
//        $sig=md5("/ws/geocoder/v1/?key=".Yii::$app->params["mapKey"]."&address=".$this->address.Yii::$app->params['mapSk']);
//        $url.='&sig='.$sig;
        $curl = new Curl();
        $response = $curl->get($url);
        if ($curl->responseCode == 200) {
            //Yii::error($response);
            $response = json_decode($response, true);
            if ($response["status"] != 0) {
                if ($response["status"] == 347) {
                    $this->addError('save', '商户地址定位不到，请填写下正确的地址。');
                    return FALSE;
                } else {
                    $this->addError('save', '商户地址定位失败(' . $response["message"] . ')。');
                    return FALSE;
                }
            } else {
                $longitude = $response["result"]["location"]["lng"] . "";
                $latitude = $response["result"]["location"]["lat"] . "";
            }
        } else {
            $this->addError('save', '商户地址定位请求失败(' . $curl->responseCode . ')。');
            return FALSE;
        }
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $business->sort_id = $this->sort_id;
            $business->vip_id = $vip_id;
            $business->points = 0;
            $business->name = $this->name;
            $business->contacts = $this->contacts;
            $business->phone = $this->phone;
            $business->address = $this->address;
            //$business->license_path = $this->license_path;
            $business->longitude = $longitude;
            $business->latitude = $latitude;
            $business->status = 1;
            $business->is_audit = 0;
            if (!$business->save())
                return false;
            $material = new BusinessMaterial;
            $material->business_id = $business->id;
            $material->email = $this->email;
            $material->license_path = $this->license_path;
            $material->bank_card = $this->bank_card;
            $material->bank_add = $this->bank_add;
            $material->card_path1 = $this->card_path1;
            $material->card_path2 = $this->card_path2;
            $material->food_license_path = $this->food_license_path;
            if (!$material->save())
                return false;
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
