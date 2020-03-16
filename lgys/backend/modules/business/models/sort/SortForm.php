<?php

namespace backend\modules\business\models\sort;

use Yii;
use common\models\BusinessSort;
use yii\base\Model;

/**
 * 商户分类表单
 */
class SortForm extends Model {

    public $id;
    public $name;

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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'name' => '名称'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $sort = new BusinessSort;
        if ($this->id > 0) {
            $sort= BusinessSort::find()->where(['id'=>$this->id])->one();
            if (!$sort) {
                $this->addError('save', '分类信息不存在。');
                return FALSE;
            }
        }
        $sort->name=$this->name;
        if($this->id==0){
            $order_num = 1;
            $max= BusinessSort::find()->orderBy('order_num desc')->limit(1)->one();
            if ($max)
                $order_num = ++$max->order_num;
            $sort->order_num=$order_num;
        }
        return $sort->save();
    }

}
