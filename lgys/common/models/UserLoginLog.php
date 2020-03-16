<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_login_log".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property string $ip IP
 * @property string $username 用户名
 * @property string $name
 * @property string $created_at 登陆日期
 */
class UserLoginLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_login_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'ip'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['ip', 'name'], 'string', 'max' => 50],
            [['username'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ip' => 'Ip',
            'username' => 'Username',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }
}
