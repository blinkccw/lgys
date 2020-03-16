<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notice_task".
 *
 * @property int $id
 * @property int $business_id 商户ID
 * @property string $face_path 通知封面
 * @property int $term 条件
 * @property string $title 标题
 * @property string $msg 内容
 * @property int $status 状态
 * @property int $is_replace 是否平台代发
 * @property string $created_at 创建日期
 */
class NoticeTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_id', 'title'], 'required'],
            [['business_id', 'term', 'status', 'is_replace'], 'integer'],
            [['msg'], 'string'],
            [['created_at'], 'safe'],
            [['face_path', 'title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_id' => '商户ID',
            'face_path' => '通知封面',
            'term' => '条件',
            'title' => '标题',
            'msg' => '内容',
            'status' => '状态',
            'is_replace' => '是否平台代发',
            'created_at' => '创建日期',
        ];
    }
}
