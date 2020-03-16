<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\AllianceBusiness;
use yii\base\Model;

/**
 * 删除商户联盟
 *
 * @author xjx
 */
class AllianceDelete extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id'
        ];
    }

    /**
     * 删除操作
     */
    public function delete() {
        $alliance = AllianceBusiness::find()->where(['id' => $this->id])->one();
        if (!$alliance) {
            $this->addError('save', '商户联盟信息不存在。');
            return FALSE;
        }
        return  $alliance->delete();;
    }

}
