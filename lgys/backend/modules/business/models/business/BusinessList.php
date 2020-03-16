<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\Business;
use common\core\BasePageModel;
use common\core\WxBusiness;
use common\models\BusinessPoints;

/**
 * 商户列表
 */
class BusinessList extends BasePageModel {

    public $sort_id;
    public $grade_id;
    public $key;
    public $is_audit;
    public $status;
    public $min_points;
    public $max_points;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['sort_id', 'trim'],
            ['sort_id', 'integer'],
            ['grade_id', 'trim'],
            ['grade_id', 'integer'],
            ['key', 'trim'],
            ['key', 'string'],
            ['is_audit', 'trim'],
            ['is_audit', 'integer'],
            ['status', 'trim'],
            ['status', 'integer'],
            ['min_points', 'trim'],
            ['min_points', 'integer'],
            ['max_points', 'trim'],
            ['max_points', 'integer']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'sort_id' => '商户分类',
            'grade_id' => '商户等级',
            'key' => '关键字',
            'is_audit' => '审核状态',
            'status' => '状态',
            'min_points' => '最低代币',
            'max_points' => '最高代币'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data = [];
        $dataPage = Business::find()->where($data);
        if ($this->key != '') {
            $dataPage->andWhere(['or', ['like', 'name', $this->key], ['like', 'contacts', $this->key], ['like', 'phone', $this->key]]);
        }
        if ($this->sort_id != null && $this->sort_id > 0) {
            $dataPage->andWhere(['sort_id' => $this->sort_id]);
        }
        if ($this->grade_id != null && $this->grade_id >= 0) {
            $dataPage->andWhere(['grade_id' => $this->grade_id]);
        }
        if ($this->status != null && $this->status >= 0) {
            $dataPage->andWhere(['status' => $this->status]);
        }
        if ($this->is_audit != null && $this->is_audit >= 0) {

            $dataPage->andWhere(['is_audit' => $this->is_audit]);
        } else {
            $dataPage->andWhere(['is_audit' => [0, 2]]);
        }
        if ($this->min_points != null && $this->min_points > 0)
            $dataPage->andWhere(['>=', 'points', $this->min_points]);
        if ($this->max_points != null && $this->max_points > 0)
            $dataPage->andWhere(['<=', 'points', $this->max_points]);
        $counts = $dataPage->count();
        $list = $dataPage->with(['vip', 'sort', 'grade'])->orderBy('id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        $qr_dir = Yii::getAlias('@frontend/web/qr/b');
        if (!file_exists($qr_dir))
            mkdir($qr_dir, 0777, true);
        $wx = new WxBusiness;
        foreach ($list as $k => $v) {
            $qr_file = $qr_dir . '/b_' . $list[$k]['id'] . '.png';
            if (!file_exists($qr_file)) {
                $wx->createQr($qr_file, $list[$k]['id'], 'pages/index/index');
            }
            $list[$k]['qr_url'] = Yii::$app->params['WEB_URL'] . '/qr/b/b_' . $list[$k]['id'] . '.png';
        }
        if($list){
            $all_id= array_column($list, 'id');
           $points=BusinessPoints::find()->where(['business_id' => $all_id, 'points_type' => 2,'flag'=>1])->select('business_id,sum(points) as ercentage')->groupBy('business_id')->asArray()->all();
            foreach ($list as $k => $v) {
                $list[$k]['ercentage']=0;
                foreach ($points as $tem){
                    if($list[$k]['id']==$tem['business_id']&&$tem['ercentage']>0){
                         $list[$k]['ercentage']=$tem['ercentage'];
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
