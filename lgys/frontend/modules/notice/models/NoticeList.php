<?php

namespace frontend\modules\notice\models;

use Yii;
use common\models\Notice;
use common\core\BasePageModel;

/**
 * 消息列表
 */
class NoticeList extends BasePageModel {

    public $vip_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['vip_id', 'trim'],
            [['vip_id'], 'required'],
            ['vip_id', 'integer']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'vip_id' => '会员ID'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data['vip_id']= $this->vip_id;
        $dataPage = Notice::find()->where($data);
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
