<?php

namespace backend\modules\business\models\grade;

use Yii;
use common\models\Business;
use common\models\BusinessGrade;
use yii\base\Model;

/**
 * 删除等级
 *
 * @author xjx
 */
class GradeDelete extends Model {

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
        $grade = BusinessGrade::find()->where(['id' => $this->id])->one();
        if (!$grade) {
            $this->addError('save', '等级信息不存在。');
            return FALSE;
        }
        //事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($grade->delete()) {
                Business::updateAll(['grade_id'=>0],['grade_id'=>$this->id]);
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return false;
    }

}
