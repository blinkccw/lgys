<?php

namespace frontend\modules\vip\models\alliance;

use Yii;
use common\models\AllianceBusiness;
use common\models\Business;
use common\core\BasePageModel;

/**
 * 获取商户列表（不包含在联盟中的）
 *
 * @author xjx
 */
class BusinessList extends BasePageModel {

    public $id;
    public $alliance_id;
    public $key;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'integer'],
            ['alliance_id', 'trim'],
            ['alliance_id', 'required'],
            ['alliance_id', 'integer'],
            ['key', 'trim'],
            ['key', 'required'],
            ['key', 'string']
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
            'key' => '关键字'
        ];
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * 获取信息
     */
    public function getList($page_size = 10) {
        $this->page_size = $page_size;
        $data = [];
        $subPage = (new \yii\db\Query())->select('business_id')->from('alliance_business')->where(['alliance_id' => $this->alliance_id])->andWhere('alliance_business.business_id=business.id');
        $dataPage = Business::find()->where(['not exists', $subPage]);
        if ($this->key != '') {
            $dataPage->andWhere(['like', 'name', $this->key]);
        }
        $counts = $dataPage->count();
        $list = $dataPage->orderBy('id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        if ($list) {
            foreach ($list as $k => $v) {
                if ($list[$k]['face_path']) {
                    $list[$k]['face_path'] = Yii::$app->params['WEB_URL'] . $list[$k]['face_path'];
                }
            }
        }
        $rel['list'] = $list;
        $rel['page'] = $this->getPageData($counts);
        return $rel;
    }

}
