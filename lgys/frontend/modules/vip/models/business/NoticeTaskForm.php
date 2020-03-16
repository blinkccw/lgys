<?php

namespace frontend\modules\vip\models\business;

use Yii;
use common\models\NoticeTask;
use common\models\NoticeTaskImg;
use yii\base\Model;

/**
 * 消息任务
 */
class NoticeTaskForm extends Model {

    public $business_id;
    public $face_path;
    public $term;
    public $title;
    public $msg;
    public $img_list;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['business_id'], 'trim'],
            [['business_id'], 'required'],
            [['business_id'], 'integer'],
            [['face_path'], 'trim'],
            [['face_path'], 'string', 'max' => 100],
            [['term'], 'trim'],
            [['term'], 'required'],
            [['term'], 'integer'],
            [['title'], 'trim'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 100],
            [['msg'], 'string', 'max' => 500],
            [['img_list'], 'trim'],
            [['img_list'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'business_id' => '商户ID',
            'face_path'=>'封面',
            'term' => '条件',
            'title' => '标题',
            'msg' => '消息内容',
            'img_list' => '图片'
        ];
    }

    /**
     * 保存
     */
    public function save($vip_id) {
        $task = new NoticeTask;
        $task->business_id = $this->business_id;
        $task->face_path = $this->face_path;
        $task->term = $this->term;
        $task->title = $this->title;
        $task->msg = $this->msg;
        $rel = $task->save();
        if ($rel) {
            $img_list = json_decode($this->img_list, true);
            if ($img_list) {
                foreach ($img_list as $img) {
                    $task_img = new NoticeTaskImg;
                    $task_img->notice_task_id = $task->id;
                    $task_img->img_path = $img['path'];
                    $task_img->save();
                }
            }
        }
        return $rel;
    }

}
