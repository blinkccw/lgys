<?php

namespace backend\modules\business\models\sort;

use Yii;
use common\models\BusinessSort;
use yii\base\Model;

/**
 * 分类排序
 *
 * @author xjx
 */
class SortOrder extends Model {

    public $id;
    public $order_num;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['order_num'], 'trim'],
            [['order_num'], 'required'],
            [['order_num'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'order_num' => '排序'
        ];
    }

    /**
     * 操作
     */
    public function save() {
        $sort= BusinessSort::find()->where(['id' => $this->id])->one();
        if (!$sort) {
            $this->addError('save', '分类信息不存在。');
            return FALSE;
        }
        $sort->order_num = $this->order_num;
        return $sort->save();
    }

}
