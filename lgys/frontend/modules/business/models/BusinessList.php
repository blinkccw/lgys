<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\Business;
use common\core\BasePageModel;
use common\core\CommonFun;

/**
 * 商户列表
 */
class BusinessList extends BasePageModel {

    public $sort_id;
    public $key;
    public $order_distance;
    public $order_pre;
    public $latitude;
    public $longitude;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['sort_id', 'trim'],
            ['sort_id', 'integer'],
            ['key', 'trim'],
            ['key', 'string'],
            ['order_distance', 'trim'],
            ['order_distance', 'integer'],
            ['order_pre', 'trim'],
            ['order_pre', 'integer'],
            [['latitude', 'longitude'], 'number']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'sort_id' => '商户分类',
            'key' => '关键字',
            'order_distance' => '',
            'order_pre' => '',
            'latitude' => '',
            'longitude' => ''
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($vip_id, $page_size = 10) {
        $this->page_size = $page_size;
        $data['status'] = 1;
        $data['is_audit'] = 1;
        $dataPage = Business::find()->where($data);
        if ($this->key != null && $this->key != '') {
            $dataPage->andWhere(['like', 'name', $this->key]);
        }
        if ($this->sort_id != null && $this->sort_id > 0) {
            $dataPage->andWhere(['sort_id' => $this->sort_id]);
        }
        $counts = $dataPage->count();
        $dataPage->with(['face', 'lastAlliance', 'lastAlliance.alliance', 'sort']);
        if (($this->latitude == null || $this->latitude == 0) && ($this->longitude == null || $this->longitude == 0)) {
            $dataPage->orderBy('id desc');
        } else {
            $order_by = "distance asc";
            $dataPage->select('*,(ACOS(SIN((' . $this->latitude . ' * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) +COS((' . $this->latitude . ' * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS((' . $this->longitude . ' * 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380) as distance')->orderBy($order_by);
            if ($this->order_distance != null) {
                switch ($this->order_distance) {
                    case 1:
                        $dataPage->having('distance<=0.5');
                        break;
                    case 2:
                        $dataPage->having('distance<=1');
                        break;
                    case 3:
                        $dataPage->having('distance<=2');
                        break;
                }
            }
        }
        if ($this->order_pre != null) {
            switch ($this->order_pre) {
                case 1:
                    $dataPage->andWhere(['between', 'deduction_pre', 0, 10]);
                    break;
                case 2:
                    $dataPage->andWhere(['between', 'deduction_pre', 10, 20]);
                    break;
                case 3:
                    $dataPage->andWhere(['between', 'deduction_pre', 20, 30]);
                    break;
                case 4:
                    $dataPage->andWhere(['between', 'deduction_pre', 30, 40]);
                    break;
                case 5:
                    $dataPage->andWhere(['between', 'deduction_pre', 40, 50]);
                    break;
                case 6:
                    $dataPage->andWhere(['between', 'deduction_pre', 50, 60]);
                    break;
                case 7:
                    $dataPage->andWhere(['between', 'deduction_pre', 60, 70]);
                    break;
                case 8:
                    $dataPage->andWhere(['between', 'deduction_pre', 70, 80]);
                    break;
                case 9:
                    $dataPage->andWhere(['between', 'deduction_pre', 80, 90]);
                    break;
                case 10:
                    $dataPage->andWhere(['between', 'deduction_pre', 90, 100]);
                    break;
            }
        }
        $list = $dataPage->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        foreach ($list as $k => $v) {
            if ($list[$k]['per'] > 0) {
                $list[$k]['per'] = round($list[$k]['per'], 2);
            }
            if ($list[$k]['face_path']) {
                $list[$k]['face_path'] = Yii::$app->params['WEB_URL'] . $list[$k]['face_path'];
            }
            if ($list[$k]['face']) {
                $list[$k]['face']['img_path'] = Yii::$app->params['WEB_URL'] . $list[$k]['face']['img_path'];
            }
            if (!isset($list[$k]['distance'])) {
                $list[$k]['distance'] = '';
            } else {
                $list[$k]['distance'] = floor($list[$k]['distance'] * 1000);
                if ($list[$k]['distance'] < 1000) {
                    $list[$k]['distance'] .= 'm';
                } else if ($list[$k]['distance'] > 1000 * 100) {
                    $list[$k]['distance'] = '100km+';
                } else {
                    $list[$k]['distance'] = round($list[$k]['distance'] / 1000, 1) . 'km';
                }
            }
        }
        if ($list) {
            $all_id = array_column($list, 'id');
            $alliance_bussiness= Business::find()
                    ->leftJoin('vip_points','business.id=vip_points.business_id')
                    ->leftJoin('alliance_business','business.id=alliance_business.business_id')
                    ->where(['vip_points.vip_id'=>$vip_id,'business.id'=>$all_id])
                    ->andWhere(['is not','alliance_business.business_id',null])
                    ->select('business.id')
                    ->asArray()
                    ->all();
             foreach ($list as $k => $v){
                 $list[$k]['is_alliance']=0;
                 foreach ($alliance_bussiness as $tem_bussiness){
                     if($list[$k]['id']==$tem_bussiness['id']){
                         $list[$k]['is_alliance']=1;
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
