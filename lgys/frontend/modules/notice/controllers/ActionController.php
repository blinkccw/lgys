<?php

namespace frontend\modules\notice\controllers;

use Yii;
use frontend\controllers\BaseVipController;
use common\models\Notice;

/**
 * 消息操作
 */
class ActionController extends BaseVipController {

    public function actionNoReads() {
        $no_reads = Notice::find()->where(['vip_id' => $this->vip_id, 'is_read' => 0])->count('id');
        return $this->sucJson(['no_reads' => $no_reads]);
    }

    public function actionSetAllRead() {
        $rel = Notice::updateAll(['is_read' => 1, 'readed_at' => date('Y-m-d H:i:s')], ['vip_id' => $this->vip_id, 'is_read' => 0]);
        return $this->relJson($rel);
    }

    /**
     * 获取消息列表
     */
    public function actionGetList() {
        $model = new \frontend\modules\notice\models\NoticeList;
        $model->setAttributes($this->post);
        $rel = $model->getList(20);
        return $this->sucJson($rel);
    }

    /**
     * 获取信息
     */
    public function actionGetInfo() {
        $notice = Notice::find()->where(['id' => $this->post['id'], 'vip_id' => $this->vip_id])->with(['imgs'])->asArray()->one();
        if (!$notice) {
            return $this->errorJson('消息已经不存在');
        }
        if ($notice['is_read'] == 0) {
            Notice::updateAll(['is_read' => 1, 'readed_at' => date('Y-m-d H:i:s')], ['id' => $notice['id']]);
        }
        if($notice['imgs']){
              foreach ($notice['imgs'] as $k => $v) {
                    if ($notice['imgs'][$k]['img_path']) {
                        $notice['imgs'][$k]['img_path'] = Yii::$app->params['WEB_URL'] . $notice['imgs'][$k]['img_path'];
                    }
                }
        }
        return $this->sucJson(['notice' => $notice]);
    }

    /**
     * 删除
     */
    public function actionDelete() {
        $notice = Notice::find()->where(['id' => $this->post['id'], 'vip_id' => $this->vip_id])->one();
        if (!$notice) {
            return $this->errorJson('消息已经不存在');
        }
        return $this->relJson($notice->delete());
    }

}
