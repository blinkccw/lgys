<?php

namespace frontend\modules\vip\models\business;

use Yii;
use common\models\Pay;
use common\core\BasePageModel;

/**
 * 获取消费记录
 *
 * @author xjx
 */
class PayList extends BasePageModel {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'integer']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'id' => '商户ID'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 获取信息
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data['business_id'] = $this->id;
        $data['status'] = 1;
        $dataPage = Pay::find()->where($data);
        $counts = $dataPage->count();
        $list = $dataPage->orderBy('id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->with(['vip'])
                        ->asArray()->all();
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
