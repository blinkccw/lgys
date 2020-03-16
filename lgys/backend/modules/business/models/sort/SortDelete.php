<?php

namespace backend\modules\business\models\sort;

use Yii;
use common\models\Business;
use common\models\BusinessSort;
use yii\base\Model;

/**
 * 删除分类
 *
 * @author xjx
 */
class SortDelete extends Model {

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
        $sort = BusinessSort::find()->where(['id' => $this->id])->one();
        if (!$sort) {
            $this->addError('save', '分类信息不存在。');
            return FALSE;
        }
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($sort->delete()) {
                Business::updateAll(['sort_id'=>0],['sort_id'=>$this->id]);
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
