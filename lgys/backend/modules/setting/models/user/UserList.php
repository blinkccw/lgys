<?php

namespace backend\modules\setting\models\user;

use Yii;
use common\models\User;
use common\core\BasePageModel;

/**
 * 管理员列表
 */
class UserList extends BasePageModel {

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
        $data = [];
        $dataPage = User::find()->where($data);
        if ($this->key != '') {
            $dataPage->andWhere(['or',['like', 'name', $this->key],['like', 'username', $this->key]]);
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
