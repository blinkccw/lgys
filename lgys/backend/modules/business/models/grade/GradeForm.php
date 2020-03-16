<?php

namespace backend\modules\business\models\grade;

use Yii;
use common\models\BusinessGrade;
use yii\base\Model;

/**
 * 商户等级表单
 */
class GradeForm extends Model {

    public $id;
    public $name;
    public $vip_num;
    public $commission;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['vip_num'], 'trim'],
            [['vip_num'], 'required'],
            [['vip_num'], 'integer'],
            [['commission'], 'trim'],
            [['commission'], 'required'],
            [['commission'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'name' => '名称',
            'vip_num' => '会员数量',
            'commission'=>'抽成比例'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $grade = new BusinessGrade();
        if ($this->id > 0) {
            $grade = BusinessGrade::find()->where(['id' => $this->id])->one();
            if (!$grade) {
                $this->addError('save', '等级信息不存在。');
                return FALSE;
            }
        }
        $count = BusinessGrade::find()->where(['<>', 'id', $this->id])->andWhere(['vip_num' => $this->vip_num])->count('id');
        if ($count > 0) {
            $this->addError('save', '已经有等级有相同会员数量。');
            return FALSE;
        }
        $grade->name = $this->name;
        $grade->vip_num = $this->vip_num;
        $grade->commission = $this->commission;
        return $grade->save();
    }

}
