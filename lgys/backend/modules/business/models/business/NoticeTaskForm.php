<?php

namespace backend\modules\business\models\business;

use Yii;
use common\models\NoticeTask;
use common\models\NoticeTaskImg;
use yii\base\Model;

/**
 * 消息任务
 */
class NoticeTaskForm extends Model {

    public $id;
    public $face_path;
    public $term;
    public $title;
    public $editorValue;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['face_path'], 'trim'],
            [['face_path'], 'string', 'max' => 100],
            [['term'], 'trim'],
            [['term'], 'required'],
            [['term'], 'integer'],
            [['title'], 'trim'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 100],
            [['editorValue'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '商户ID',
            'face_path'=>'封面',
            'term' => '条件',
            'title' => '标题',
            'editorValue' => '消息内容'
        ];
    }

    /**
     * 保存
     */
    public function save() {
        $task = new NoticeTask;
        $task->business_id = $this->id;
        $task->face_path = $this->face_path;
        $task->term = $this->term;
        $task->title = $this->title;
        $task->msg = $this->editorValue;
        $task->is_replace=1;
        return $task->save();
    }

}
