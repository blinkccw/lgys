<?php

namespace backend\modules\alliance\models\alliance;

use Yii;
use common\models\Alliance;
use yii\base\Model;

/**
 * 联盟推荐状态
 *
 * @author xjx
 */
class AllianceIshot extends Model {

    public $id;
    public $is_hot;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['is_hot'], 'trim'],
            [['is_hot'], 'required'],
            [['is_hot'], 'integer']];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id',
            'is_hot'=>'是否推荐'
        ];
    }

    /**
     * 删除操作
     */
    public function save() {
        $alliance = Alliance::find()->where(['id' => $this->id])->one();
        if (!$alliance) {
            $this->addError('save', '商户信息不存在。');
            return FALSE;
        }
        $alliance->is_hot = $this->is_hot;
        return $alliance->save();
    }

}
