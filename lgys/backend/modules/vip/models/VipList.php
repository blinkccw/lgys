<?php

namespace backend\modules\vip\models;

use Yii;
use common\models\Vip;
use common\core\BasePageModel;

/**
 * 用户列表
 */
class VipList extends BasePageModel {

    public $key;
    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['key', 'trim'],
            ['key', 'string']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'key' => '关键字'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data['is_auth'] = 1;
        $dataPage = Vip::find()->where($data);
        if ($this->key != '') {
            $dataPage->andWhere(['or', ['like', 'name', $this->key], ['like', 'nick_name', $this->key],['like', 'vip_no', $this->key]]);
        }
        $counts = $dataPage->count();
        $list = $dataPage->orderBy('id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
