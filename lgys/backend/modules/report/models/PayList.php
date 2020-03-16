<?php

namespace backend\modules\report\models;

use Yii;
use common\models\Pay;
use common\models\BusinessPoints;
use common\core\BasePageModel;

/**
 * 会员消费列表
 */
class PayList extends BasePageModel {

    public $key1;
    public $key2;
    public $key3;
    public $begin_at;
    public $end_at;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['key1', 'trim'],
            ['key1', 'string'],
            ['key2', 'trim'],
            ['key2', 'string'],
            ['key3', 'trim'],
            ['key3', 'string'],
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
            'key1' => '联盟关键字',
            'key2' => '商户关键字',
            'key3' => '用户关键字',
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
        $data['pay.status'] = 1;
        $dataPage = Pay::find()->where($data);
        if ($this->begin_at != '') {
            $dataPage->andWhere(['>=', 'pay.created_at', $this->begin_at]);
        }
        if ($this->end_at != '') {
            $dataPage->andWhere(['<=', 'pay.created_at', $this->end_at . ' 23:59:59']);
        }
        if ($this->key1 != '') {
            $dataPage->leftJoin('alliance', 'pay.alliance_id=alliance.id')->andWhere(['like', 'alliance.name', $this->key1]);
        }
        if ($this->key2 != '') {
            $dataPage->leftJoin('business', 'pay.business_id=business.id')->andWhere(['like', 'business.name', $this->key2]);
        }
        if ($this->key3 != '') {
            $dataPage->leftJoin('vip', 'pay.vip_id=vip.id')->andWhere(['or', ['like', 'vip.vip_no', $this->key3], ['like', 'vip.nick_name', $this->key3]]);
        }
        $counts = $dataPage->count();
        $list = $dataPage->with(['business', 'alliance', 'vip'])->select('pay.*')->orderBy('pay.id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        if ($list) {
            $all_id = array_column($list, 'id');
            $all_ercentage = BusinessPoints::find()->where(['points_type' => 2, 'flag' => 1, 'pay_id' => $all_id])->groupBy('pay_id')->select('pay_id,sum(points) as points')->asArray()->all();
            foreach ($list as $k => $v) {
                $list[$k]['ercentage']=0;
                foreach ($all_ercentage as $ercentage) {
                    if($list[$k]['id']==$ercentage['pay_id']){
                         $list[$k]['ercentage']=$ercentage['points']>0?round($ercentage['points'],1):0;
                        break;
                    }
                }
            }
        }
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
