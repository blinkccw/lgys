<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\Business;
use yii\base\Model;
use common\models\Notice;
/**
 * 商户审核状态
 *
 * @author xjx
 */
class BusinessAudit extends Model {

    public $id;
    public $is_audit;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['is_audit'], 'trim'],
            [['is_audit'], 'required'],
            [['is_audit'], 'integer']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'is_audit' => '审核状态'
        ];
    }

    /**
     * 删除操作
     */
    public function save() {
        $business = Business::find()->where(['id' => $this->id])->one();
        if (!$business) {
            $this->addError('save', '商户信息不存在。');
            return FALSE;
        }
        $business->is_audit = $this->is_audit;
        $rel = $business->save();
//        if ($rel) {
//            $notice = new Notice;
//            $notice->title = "商户审核结果通过";
//            $notice->vip_id = $business->vip_id;
//            $notice->msg = "您的商户[".$business->name."]".($this->is_audit==1?"已审核通过。":"审核不通过");
//            $notice->save();
//        }
        return $rel;
    }

}
