<?php

namespace backend\modules\business\models\log;

use Yii;
use common\models\VipPointsLog;
use common\core\BasePageModel;

/**
 * 商户代币日志列表
 */
class LogList extends BasePageModel {

    public $id;
    public $alliance_id;
    public $flag;
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
            ['alliance_id', 'trim'],
            ['alliance_id', 'integer'],
            ['flag', 'trim'],
            ['flag', 'required'],
            ['flag', 'integer'],
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
            'id' => '商户ID',
            'alliance_id' => '联盟ID',
            'flag' => '',
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
        $data['vip_points_log.business_id'] = $this->id;
        $data['vip_points_log.flag'] = $this->flag;
        $data['vip_points_log.status'] = 1;
        $dataPage = VipPointsLog::find()->where($data);
        if ($this->alliance_id > 0) {
            $dataPage->andWhere(['vip_points_log.alliance_id' => $this->alliance_id]);
        }
        if ($this->begin_at != '') {
            $dataPage->andWhere(['>=', 'vip_points_log.created_at', $this->begin_at]);
        }
        if ($this->end_at != '') {
            $dataPage->andWhere(['<=', 'vip_points_log.created_at', $this->end_at . ' 23:59:59']);
        }
        if ($this->key != '') {
            $dataPage->leftJoin('vip', 'vip_points_log.vip_id=vip.id')->andWhere(['or', ['like', 'vip.vip_no', $this->key], ['like', 'vip.nick_name', $this->key]]);
        }
        $counts = $dataPage->count();
        $list = $dataPage->with(['vip', 'alliance'])->select('vip_points_log.*')->orderBy('vip_points_log.id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
