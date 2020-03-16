<?php

namespace frontend\modules\vip\models\business;

use Yii;
use common\models\Pay;
use yii\base\Model;

/**
 * 商户日志首页
 *
 * @author xjx
 */
class LogIndex extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            ['id', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '商户ID'
        ];
    }

    /**
     * 获取首页信息
     */
    public function index($vip_id) {
        $page = Pay::find()->where(['business_id' => $this->id, 'status' => 1])
                ->andWhere(['>', 'created_at', date('Y-m-d')]);
        $data['pay']['points'] = $page->sum('used_point');
        $data['pay']['money'] = $page->sum('pay');
        if ($data['pay']['points'] == null)
            $data['pay']['points'] = 0;
        if ($data['pay']['money'] == null)
            $data['pay']['money'] = 0;
        return $data;
    }

}
