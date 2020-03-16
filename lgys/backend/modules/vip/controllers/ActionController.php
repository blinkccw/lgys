<?php

namespace backend\modules\vip\controllers;

use yii;
use backend\controllers\BaseActionController;
use common\core\CommonFun;

/**
 * 用户请求控制器
 */
class ActionController extends BaseActionController {

    /**
     * 导出用户
     */
    public function actionExportVip() {
        $model = new \backend\modules\vip\models\VipList;
        try {
            $page_index = 1;
            $file_name = 'vip_' . time() . '_' . CommonFun::getRandom() . '.xls';
            $title[] = '会员号';
            $title[] = '昵称';
            $title[] = '消费总金额';
            $title[] = '代币余额';
            $title[] = '代币销毁数';
            $title[] = '创建日期';
            // $this->saveExcel($file_name, $title);
            $data[] = $title;
            do {
                $this->post['page_index'] = $page_index++;
                $model->setAttributes($this->post);
                $rel = $model->getList(100);
                if (!$rel['list'])
                    break;
                foreach ($rel['list'] as $item) {
                    $row = [];
                    $row[] = $item['vip_no'];
                    $row[] = $item['name'];
                    $row[] = $item['total'];
                    $row[] = $item['points'];
                    $row[] = $item['used_points'];
                    $row[] = $item['created_at'];
                    $data[] = $row;
                }
            } while (true);
            $this->saveExcel($file_name, $data);
            return $this->sucJson(['file' => $file_name]);
        } catch (Exception $e) {
            Yii::error('导出excel:' . $e);
        }
        return $this->errorJson();
    }

}
