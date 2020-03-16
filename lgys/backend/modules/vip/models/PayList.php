<?php

namespace backend\modules\vip\models;

use Yii;
use common\models\Pay;
use common\core\BasePageModel;

/**
 * 会员消费列表
 */
class PayList extends BasePageModel {

    public $id;
    public $key;
    public $begin_at;
    public $end_at;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'integer'],
            ['key', 'trim'],
            ['key', 'string'],
            [['begin_at', 'end_at'], 'trim'],
            [['begin_at', 'end_at'], 'safe']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'id' => '会员ID',
            'key' => '关键字',
            'begin_at' => '开始日期',
            'end_at' => '结束日期'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data['pay.vip_id'] = $this->id;
        $data['pay.status'] = 1;
        $dataPage = Pay::find()->where($data);
        if ($this->begin_at != '') {
            $dataPage->andWhere(['>=', 'pay.created_at', $this->begin_at]);
        }
        if ($this->end_at != '') {
            $dataPage->andWhere(['<=', 'pay.created_at', $this->end_at . ' 23:59:59']);
        }
        if ($this->key != '') {
            $dataPage
                    ->leftJoin('business', 'pay.business_id=business.id')
                    ->leftJoin('alliance', 'pay.alliance_id=alliance.id')
                    ->andWhere(['or', ['like', 'business.name', $this->key], ['like', 'alliance.name', $this->key]]);
        }
        $counts = $dataPage->count();
        $list = $dataPage->with(['business', 'alliance'])->select('pay.*')->orderBy('pay.id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
