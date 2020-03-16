<?php

namespace frontend\modules\vip\models\alliance;

use Yii;
use common\models\AllianceBusiness;
use common\core\BasePageModel;

/**
 * 获取联盟记录列表
 *
 * @author xjx
 */
class AllianceList extends BasePageModel {

    public $id;
    public $is_host;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'integer'],
            ['is_host', 'trim'],
            ['is_host', 'required'],
            ['status', 'trim'],
            ['status', 'required']
        ];
        return array_merge($rules, parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = [
            'id' => '商户ID',
            'is_host' => '',
            'status' => '状态'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 获取信息
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data['business_id'] = $this->id;
        if ($this->status != null)
            $data['status'] = $this->status;
          if ($this->is_host != null)
            $data['is_host'] = $this->is_host;
        $dataPage = AllianceBusiness::find()->where($data);
        $counts = $dataPage->count();
        $list = $dataPage->orderBy('id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->with(['alliance','inviteVip'])
                        ->asArray()->all();
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
