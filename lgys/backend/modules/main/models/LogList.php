<?php

namespace backend\modules\main\models;

use Yii;
use common\core\BasePageModel;
use common\models\ReportPoints;

/**
 * 发行列表
 */
class LogList extends BasePageModel {

    public $begin_at;
    public $end_at;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
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
            'begin_at' => '开始日期',
            'end_at' => '结束日期'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($page_size = 10) {
        $all_begin_at = '2019-4-1';
        if ($this->begin_at != null && $this->begin_at != '')
            $all_begin_at = $this->begin_at;
        $end_at = date('Y-m-d', strtotime('-1 day'));
        if ($this->end_at != null && $this->end_at != '')
            $end_at = $this->end_at;
        $counts = ceil((strtotime($end_at) - strtotime($all_begin_at)) / 3600 / 24);
        if($counts>0)
            $counts++;
        $this->page_size = $page_size;
        if ($counts < 0) {
            $counts = 0;
            $rel['list'] = [];
            $rel['page'] = $this->getPageData($counts);
            return $rel;
        }
        $end_at = date('Y-m-d', strtotime('-' . (($this->page_index - 1) * $this->page_size) . ' days', strtotime($end_at)));
        $begin_at = date('Y-m-d', strtotime('-' . ($this->page_size - 1) . ' days', strtotime($end_at)));
        if (strtotime($begin_at) < strtotime($all_begin_at))
            $begin_at = $all_begin_at;
        $reports = ReportPoints::find()->where(['between', 'created_at', $begin_at, $end_at])->all();
        $days = round((strtotime($end_at) - strtotime($begin_at)) / 3600 / 24);
        $list = [];
        for ($i = 0; $i <= $days; $i++) {
            $item = ['day' => date('Y-m-d', strtotime('-' . $i . ' days', strtotime($end_at))), 'exchange_points' => 0,'deduction_points'=>0];
            foreach ($reports as $report){
                if($item['day']==date('Y-m-d', strtotime($report->created_at))){
                    $item['exchange_points']=$report->exchange_points;
                     $item['deduction_points']=$report->deduction_points;
                    break;
                }
            }
            $list[] = $item;
        }

        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
