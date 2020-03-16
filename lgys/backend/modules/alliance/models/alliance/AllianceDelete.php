<?php

namespace backend\modules\alliance\models\alliance;

use Yii;
use common\models\Alliance;
use common\models\AllianceBusiness;
use yii\base\Model;

/**
 * 删除联盟
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
        $alliance = Alliance::find()->where(['id' => $this->id])->one();
        if (!$alliance) {
            $this->addError('save', '联盟信息不存在。');
            return FALSE;
        }
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($alliance->delete()) {
                AllianceBusiness::deleteAll(['alliance_id'=>$alliance->id]);
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
