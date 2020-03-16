<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Notice;
use common\models\NoticeTask;
use common\models\Alliance;
use common\models\AllianceBusiness;
use common\models\Vip;
use common\models\VipPoints;
use common\models\VipPointsLog;
use common\models\ReportPoints;
use common\models\VipAggregation;
use common\models\VipAggregationMan;
use common\models\Business;
use common\models\BusinessPoints;

/**
 * 主程序
 */
class MainController extends Controller {

    /**
     * 发送消息任务
     */
    public function actionNoticeTask() {
        $cmd = 'ps -fe|grep notice-task |grep -v grep';
        $ret = shell_exec($cmd);
        $ret = explode("\n", rtrim($ret));
        echo count($ret) . "\n";
        if (count($ret) >= 2)
            return;
        $tasks = NoticeTask::find()->where(['status' => 0])->all();
        foreach ($tasks as $task) {
            echo $task->id . "\n";
            $vips = null;
            //发送给商户以及商户所属联盟下的会员
            $business_ids = [];
            $business_ids[] = $task->business_id;
            $all_alliance = AllianceBusiness::find()->where(['business_id' => $task->business_id])->select('alliance_id')->asArray()->all();
            if ($all_alliance) {
                $all_business = AllianceBusiness::find()->where(['alliance_id' => $all_alliance])->all();
                foreach ($all_business as $tem_business)
                    $business_ids[] = $tem_business->alliance_id;
            }
            if ($task->term == 1) {
                //全部会员
                $vips = Vip::find()->leftJoin('vip_points', 'vip.id=vip_points.vip_id')->where(['vip.status' => 1])->andWhere(['business_id' => $business_ids])->select('vip.id')->distinct()->asArray()->all();
            } else if ($task->term == 2) {
                //刚注册7天内
                $vips = Vip::find()->leftJoin('vip_points', 'vip.id=vip_points.vip_id')->where(['>=', 'vip.created_at', date('Y-m-d', strtotime('-7 day'))])->andWhere(['vip.status' => 1])->andWhere(['business_id' => $business_ids])->select('vip.id')->distinct()->asArray()->all();
            } else if ($task->term == 3) {
                //30天没有来消费的会员
                $vips = Vip::find()->leftJoin('vip_points', 'vip.id=vip_points.vip_id')->where(['>=', 'vip.pay_at', date('Y-m-d', strtotime('-30 day'))])->andWhere(['vip.status' => 1])->andWhere(['business_id' => $business_ids])->select('vip.id')->distinct()->asArray()->all();
            }
            if ($vips) {
                $transaction = Yii::$app->db->beginTransaction();
                $rel = false;
                try {
                    foreach ($vips as $vip) {
                        $notice = new Notice;
                        $notice->vip_id = $vip['id'];
                        $notice->face_path = $task->face_path;
                        $notice->business_id = $task->business_id;
                        $notice->notice_task_id = $task->id;
                        $notice->title = $task->title;
                        $notice->msg = $task->msg;
                        $notice->save();
                    }
                    $transaction->commit();
                    $rel = true;
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
                if ($rel) {
                    $task->status = 1;
                    $task->save();
                }
            }
        }
        echo "finish\n";
    }

    /**
     * 处理聚合
     * @return type
     */
    public function actionCheckAggregation() {
        $cmd = 'ps -fe|grep check-aggregation |grep -v grep';
        $ret = shell_exec($cmd);
        $ret = explode("\n", rtrim($ret));
        echo count($ret) . "\n";
        if (count($ret) >= 2)
            return;
        //处理到期的聚合
        $vip_aggregations = VipAggregation::find()->where(['status' => 0])->andWhere(['<', 'end_at', date('Y-m-d H:i:s')])->all();
        foreach ($vip_aggregations as $vip_aggregation) {
            echo $vip_aggregation->id . "\n";
            $vip_aggregation->status = 2;
            $vip_aggregation->save();
        }
        echo "mans\n";
        //退还聚合失败的代币
        $vip_aggregation_mans = VipAggregationMan::find()
                ->where(['is_return' => 0])
                ->andWhere(['exists', (new yii\db\Query())->select('id')->from('vip_aggregation')->where(['status' => 2])->andWhere('id=vip_aggregation_man.vip_aggregation_id')])
                ->with(['aggregation'])
                ->all();
        foreach ($vip_aggregation_mans as $man) {
            echo $man->id . "\n";
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $vip_points = VipPoints::find()->where(['business_id' => $man->aggregation->business_id, 'vip_id' => $man->vip_id])->one();
                if ($vip_points) {
                    $vip_points->points += $man->points;
                    if (!$vip_points->save())
                        continue;
                    $log = new VipPointsLog;
                    $log->pay_id = 0;
                    $log->business_id = $man->aggregation->business_id;
                    $log->alliance_id = 0;
                    $log->vip_id = $man->vip_id;
                    $log->points_type = 1;
                    $log->pre = 0;
                    $log->points = $man->points;
                    $log->used_points = 0;
                    $log->flag = 1;
                    $log->status = 1;
                    $log->source = 4;
                    $log->source_id = $man->aggregation->id;
                    if (!$log->save())
                        continue;
                }
                $man->is_return = 1;
                $man->return_at = date('Y-m-d H:i:s');
                if (!$man->save())
                    continue;
                $transaction->commit();
                echo "suc\n";
            } catch (Exception $ex) {
                echo "error\n";
            }
        }
        echo "finish\n";
    }

    /**
     * 代币报表
     */
    public function actionReportPoints() {
        $list = VipPointsLog::find()
                        ->where(['status' => 1, 'source' => [1, 2]])
                        ->andWhere(['>=', 'created_at', date('Y-m-d', strtotime('-7 days'))])->orderBy('id')->all();
        $reports = [];
        foreach ($list as $item) {
            echo $item->id . "\n";
            $tem_created_at = strtotime($item->created_at);
            $cur_created_at = date('Y-m-d', $tem_created_at);
            $year = date('Y', $tem_created_at);
            $month = date('m', $tem_created_at);
            $day = date('d', $tem_created_at);
            $tag = FALSE;
            foreach ($reports as $k => $v) {
                if ($year == $reports[$k]['year'] && $month == $reports[$k]['month'] && $day == $reports[$k]['day']) {
                    $tag = true;
                    $reports[$k]['exchange_points'] += ($item->flag == 1 ? $item->points : 0);
                    $reports[$k]['deduction_points'] += ($item->flag == -1 ? $item->points : 0);
                    break;
                }
            }
            if (!$tag) {
                $reports[] = [
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    'exchange_points' => ($item->flag == 1 ? $item->points : 0),
                    'deduction_points' => ($item->flag == -1 ? $item->points : 0),
                    'created_at' => date('Y-m-d', $tem_created_at),
                ];
            }
        }
        foreach ($reports as $report) {
            $log = ReportPoints::find()->where(['year' => $report['year'], 'month' => $report['month'], 'day' => $report['day']])->one();
            if (!$log) {
                $log = new ReportPoints;
                $log->year = $report['year'];
                $log->month = $report['month'];
                $log->day = $report['day'];
                $log->exchange_points = 0;
                $log->deduction_points = 0;
                $log->created_at = $report['created_at'];
            }
            $log->exchange_points = $report['exchange_points'];
            $log->deduction_points = $report['deduction_points'];
            $log->save();
        }
        echo "finish\n";
    }

    /**
     * 处理到期代币
     */
    public function actionDoPoints() {
        $logs = VipPointsLog::find()
                        ->where(['flag' => 1, 'status' => 1])
                        ->andWhere('points>used_points')
                        ->andWhere(['<', 'created_at', date('Y-m-d', strtotime('-7 days'))])
                        ->orderBy('id')->all();
        if ($logs) {
            $transaction = Yii::$app->db->beginTransaction();
            $rel = false;
            foreach ($logs as $log) {
                try {
                    echo $log->id . "\n";
                    echo $log->vip_id . "\n";
                    $points = round($log->points - $log->used_points, 1);
                    //扣除会员余额
                    $vip = Vip::find()->where(['id' => $log->vip_id])->one();
                    if ($vip) {
                        $vip->points -= $points;
                        if ($vip->points < 0)
                            $vip->points = 0;
                        if (!$vip->save())
                            break;
                        echo "vip\n";
                    }
                    //扣除会员商户余额
                    $vip_points = VipPoints::find()->where(['vip_id' => $log->vip_id, 'business_id' => $log->business_id])->one();
                    if ($vip_points) {
                        $vip_points->points -= $points;
                        if ($vip_points->points < 0)
                            $vip_points->points = 0;
                        if (!$vip_points->save())
                            break;
                        echo "vip_points\n";
                    }
                    if ($log->business_id > 0) {
                        //退回商户余额
                        $business = Business::find()->where(['id' => $log->business_id])->one();
                        if ($business) {
                            $business->points += $points;
                            if (!$business->save())
                                break;
                            echo "business\n";
                        }
                    }
                    //记录会员到期扣除日记
                    $vip_log = new VipPointsLog;
                    $vip_log->pay_id = 0;
                    $vip_log->business_id = $log->business_id;
                    $vip_log->alliance_id = 0;
                    $vip_log->vip_id = $log->vip_id;
                    $vip_log->points_type = $log->points_type;
                    $vip_log->pre = 0;
                    $vip_log->points = $points;
                    $vip_log->flag = -1;
                    $vip_log->status = 1;
                    $vip_log->source = 3;
                    if (!$vip_log->save())
                        break;
                    echo "vip_log \n";
                    //记录商户到期退回日记
                    $business_points = new BusinessPoints;
                    $business_points->points_type = 3;
                    $business_points->business_id = $log->business_id;
                    $business_points->points = $points;
                    $business_points->pre = 0;
                    $business_points->cur_pre = 0;
                    $business_points->flag = 1;
                    $business_points->pay_id = 0;
                    $business_points->is_dif = 0;
                    if (!$business_points->save())
                        break;
                    echo "business_points \n";
                    $log->used_points = $log->points;
                    if (!$log->save())
                        break;
                    $transaction->commit();
                    echo "suc\n";
                } catch (Exception $e) {
                    $transaction->rollBack();
                    echo "error\n";
                }
            }
        }
        echo "finish\n";
    }

}
