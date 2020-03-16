<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\Business;
use yii\base\Model;

/**
 * 商户
 */
class BusinessInfo extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id'
        ];
    }

    /**
     * 保存
     */
    public function info() {
        $data['id'] = $this->id;
        $data['status'] = 1;
        $data['is_audit'] = 1;
        $business = Business::find()->where($data)->with(['foodImgs', 'shopImgs', 'activitys'])->asArray()->one();
        if (!$business) {
            $this->addError('save', '商户信息不存在。');
            return FALSE;
        }
        if ($business['per'] > 0) {
            $business['per'] = round($business['per'], 2);
        }
        if ($business['face_path']) {
            $business['face_path'] = Yii::$app->params['WEB_URL'] . $business['face_path'];
        }
        if ($business['foodImgs']) {
            foreach ($business['foodImgs'] as $k => $v) {
                $business['foodImgs'][$k]['img_path'] = Yii::$app->params['WEB_URL'] . $business['foodImgs'][$k]['img_path'];
            }
        }
        if ($business['shopImgs']) {
            foreach ($business['shopImgs'] as $k => $v) {
                $business['shopImgs'][$k]['img_path'] = Yii::$app->params['WEB_URL'] . $business['shopImgs'][$k]['img_path'];
            }
        }
        return $business;
    }

}
