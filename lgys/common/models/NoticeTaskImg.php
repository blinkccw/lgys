<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notice_task_img".
 *
 * @property int $id
 * @property int $notice_task_id
 * @property string $img_path
 * @property string $created_at
 */
class NoticeTaskImg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_task_img';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notice_task_id', 'img_path'], 'required'],
            [['notice_task_id'], 'integer'],
            [['created_at'], 'safe'],
            [['img_path'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'notice_task_id' => 'Notice Task ID',
            'img_path' => 'Img Path',
            'created_at' => 'Created At',
        ];
    }
}
