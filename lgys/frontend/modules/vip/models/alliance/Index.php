<?php

namespace frontend\modules\vip\models\alliance;

use Yii;
use common\models\AllianceBusiness;
use yii\base\Model;

/**
 * 联盟首页
 *
 * @author xjx
 */
class Index extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            ['id', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '商户ID'
        ];
    }

    /**
     * 获取首页信息
     */
    public function index() {
        $data['joined_count'] = AllianceBusiness::find()->where(['business_id' => $this->id, 'status' => 1,'is_host'=>0])->count('id');
        $data['audited_count'] = AllianceBusiness::find()->where(['business_id' => $this->id, 'status' => 0])->count('id');
        $data['list']= AllianceBusiness::find()->where(['business_id' => $this->id, 'status' => 1,'is_host'=>1])->with(['alliance'])->asArray()->orderBy('id desc')->limit(100)->all();
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['created_at']=date("Y-m-d", strtotime($data['list'][$k]['created_at']));
        }
        return $data;
    }

}
