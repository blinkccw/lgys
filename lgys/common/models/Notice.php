<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notice".
 *
 * @property int $id
 * @property int $vip_id
 * @property int $business_id
 * @property int $notice_task_id
 * @property string $face_path
 * @property string $title
 * @property string $msg
 * @property int $is_read
 * @property string $readed_at
 * @property string $created_at
 */
class Notice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vip_id', 'title', 'msg'], 'required'],
            [['vip_id', 'business_id', 'notice_task_id', 'is_read'], 'integer'],
            [['msg'], 'string'],
            [['readed_at', 'created_at'], 'safe'],
            [['face_path'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_id' => 'Vip ID',
            'business_id' => 'Business ID',
            'notice_task_id' => 'Notice Task ID',
            'face_path' => 'Face Path',
            'title' => 'Title',
            'msg' => 'Msg',
            'is_read' => 'Is Read',
            'readed_at' => 'Readed At',
            'created_at' => 'Created At',
        ];
    }
    
    
    /**
     * 关联图片
     * @return type
     */
    public function getImgs() {
        return $this->hasMany(NoticeTaskImg::className(), ['notice_task_id' => 'notice_task_id'])->orderBy('id');
    }

    /**
     * 关联商户
     * @return type
     */
    public function getBusiness() {
        return $this->hasOne(Business::className(), ['id' => 'business_id'])->select('id,name,face_path');
    }
}
