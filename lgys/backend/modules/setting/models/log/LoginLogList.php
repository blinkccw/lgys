<?php

namespace backend\modules\setting\models\log;

use Yii;
use common\models\UserLoginLog;
use common\core\BasePageModel;
/**
 * 登录日志列表
 */
class LoginLogList extends BasePageModel {

    public $key;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules= [
            ['key', 'trim'],
            ['key', 'string']
        ];
        return array_merge($rules,parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels=[
            'key' => '关键字'
        ];
        return array_merge($labels,parent::attributeLabels());
    }

    /**
     * 列表
     */
    public function getList($page_size=10) {
        $this->page_size=$page_size;
        $data = [];
        $dataPage = UserLoginLog::find()->where($data);
        if ($this->key != ''){
           $dataPage->andWhere(['or',['like', 'name', $this->key],['like', 'username', $this->key]]);
        }
        $counts = $dataPage->count();
        $list = $dataPage->orderBy('id desc')
                        ->offset(($this->page_index - 1) * $this->page_size)
                        ->limit($this->page_size)
                        ->asArray()->all();
        $rel['list']=$list;
        $rel['page']=$this->getPageData($counts);
        return $rel;
    }

}
