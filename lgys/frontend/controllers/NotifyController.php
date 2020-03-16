<?php

namespace frontend\controllers;

use Yii;
use frontend\core\wx_pay\WxPayApi;
use frontend\core\wx_pay\WxPayNotifyReply;
use frontend\core\wx_pay\WxPayResults;
use common\models\Vip;
use common\models\Pay;
use common\models\Business;
use common\models\VipPoints;
use common\models\VipPointsLog;
use common\models\VipPointsUsed;
use common\models\Config;
use common\models\BusinessPoints;
use common\core\business\BisBusiness;
use common\core\CommonFun;
use yii\web\Controller;

/**
 * 支付回调页面页面
 */
class NotifyController extends Controller {

    public function beforeAction($action) {
        $action->controller->enableCsrfValidation = false;
        return true;
    }

    /**
     * 微信官网支付回调
     */
    public function actionWx() {
        $rel = $this->wxPayNotify();
        $notify_reply = new WxPayNotifyReply;
        if ($rel == false) {
            $notify_reply->SetReturn_code("FAIL");
        } else {
            $notify_reply->SetReturn_code("SUCCESS");
            $notify_reply->SetReturn_msg("OK");
        }
        WxPayApi::replyNotify($notify_reply->ToXml());
        exit();
    }

    /**
     * 微信（微信官方）在线支付回调
     */
    private function wxPayNotify() {
        $xml = file_get_contents('php://input');
        $obj = new WxPayResults;
        $val = $obj->FromXml($xml);
        if ($val['return_code'] != 'SUCCESS')
            return false;
        if (!array_key_exists("transaction_id", $val))
            return false;
        if (!array_key_exists("out_trade_no", $val))
            return false;
        //验证交易信息是否存在
        $pay = Pay::find()->where(['no' => $val['out_trade_no'], 'status' => 0])->one();
        if (!$pay)
            return false;
        try {
            if (!$obj->CheckSign(Yii::$app->params['pay_key']))
                return false;
        } catch (Exception $e) {
            return false;
        }
        if ($val['total_fee'] != intval($pay->pay * 100))
            return false;
        //存在则处理
        $transaction = Yii::$app->db->beginTransaction();
        $rel = false;
        try {
            $pay->transaction_id = $val['transaction_id'];
            $pay->pay_at = date('Y-m-d H:i:s');
            $pay->status = 1;
            $rel = $pay->save();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
        if ($rel) {
            $this->doPay($pay);
        }
        return $rel;
    }

    public function actionTest() {
        $pay = Pay::find()->where(['status' => 0])->orderBy('id desc')->one();
        //  $pay->transaction_id = $val['transaction_id'];
        $pay->pay_at = date('Y-m-d H:i:s');
        $pay->status = 1;
        $pay->save();
        $this->doPay($pay);
        die('do');
    }

    /**
     * 
     * @param type $pay
     */
    private function doPay($pay) {
        $business = Business::find()->where(['id' => $pay->business_id])->with(['grade'])->one();
        if (!$business)
            return FALSE;
        $vip = Vip::find()->where(['id' => $pay->vip_id])->one();
        if (!$vip)
            return FALSE;
        $vip_point = VipPoints::find()->where(['business_id' => $business->id, 'vip_id' => $vip->id, 'points_type' => 1])->one();
        $alliance_id = 0;
        $alliance_name = '';
        if ($business->grade) {
            $pay->business_grade_id = $business->grade->id;
            $pay->save();
        }
        //存在则处理
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //计算代币使用记录
            if ($pay->used_point > 0) {
                $term = json_decode($pay->term, true);
                if ($term) {
                    //商户代币抵扣(退回发行商)
                    if ($term['business_id'] > 0 && $term['alliance_id'] == 0) {
                        if (!$this->businessPoints($pay, $business, $vip_point))
                            return FALSE;
                    }
                    //通用代币抵扣(退回平台)
                    if ($term['business_id'] == 0 && $term['alliance_id'] == 0) {
                        if (!$this->commonPoints($pay, $business))
                            return FALSE;
                    }
                    //联盟代币抵扣(退回发行商)
                    if ($term['business_id'] == 0 && $term['alliance_id'] > 0) {
                        $alliance_id = $term['alliance_id'];
                        if (!$this->alliancePoints($pay, $business, $vip_point))
                            return FALSE;
                    }
                }
            }
            if ($pay->point > 0) {
                //发行
                if ($business->points <= $pay->point) {
                    $pay->point = $business->points;
                    $business->points = 0;
                } else {
                    $business->points -= $pay->point;
                }
                $business->exchange_points += $pay->point;
                if (!$business->save())
                    return FALSE;
                if ($pay->point > 0) {
                    $log = new VipPointsLog;
                    $log->pay_id = $pay->id;
                    $log->business_id = $business->id;
                    $log->alliance_id = $alliance_id;
                    $log->vip_id = $vip->id;
                    $log->points_type = 1;
                    $log->pre = $business->exchange_pre;
                    $log->points = $pay->point;
                    $log->used_points = 0;
                    $log->flag = 1;
                    $log->status = 1;
                    $log->source = 1;
                    if (!$log->save())
                        return FALSE;
                }
                $vip->points += $pay->point;
                $vip->total_points += $pay->point;
            }
            //操作商户
//            $business->points += $pay->used_point;
//            $business->total_points += $pay->used_point;
//            if (!$business->save())
//                return FALSE;
            $is_new = false;
            //操作商户余额
            if ($vip_point) {
                $vip_point->pay_at = date('Y-m-d H:i:s');
            } else {
                $vip_point = new VipPoints;
                $vip_point->vip_id = $pay->vip_id;
                $vip_point->points_type = 1;
                $vip_point->business_id = $business->id;
                $vip_point->points = 0;
                $vip_point->pay_at = date('Y-m-d H:i:s');
                $is_new = true;
            }
            $vip_point->points += $pay->point;
            if (!$vip_point->save())
                return FALSE;
            //操作会员
            $vip->points -= $pay->used_point;
            $vip->used_points += $pay->used_point;
            $vip->total += $pay->money;
            $vip->pay_at = date('Y-m-d H:i:s');
            if (!$vip->save())
                return FALSE;
            $pay->is_do = 1;
            if (!$pay->save())
                return FALSE;
            $transaction->commit();
            if ($is_new) {
                //是否升级
                $bis_business = new BisBusiness;
                $bis_business->upgradeGrade($business);
            }
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return false;
    }

    /**
     * 商户代币计算
     * @param type $pay
     * @param type $vip_point
     */
    private function businessPoints($pay, &$business, &$vip_point) {
        if ($vip_point && $vip_point->points > 0) {
            $vip_point->points -= $pay->used_point;
            if ($vip_point->points < 0)
                $vip_point->points = 0;
        }
        $log = new VipPointsLog;
        $log->pay_id = $pay->id;
        $log->business_id = $business->id;
        $log->alliance_id = $pay->alliance_id;
        $log->vip_id = $pay->vip_id;
        $log->points_type = 1;
        $log->pre = $business->deduction_pre;
        $log->points = $pay->used_point;
        $log->used_points = 0;
        $log->flag = -1;
        $log->status = 1;
        $log->source = 1;
        if (!$log->save())
            return FALSE;
        $used_point = $pay->used_point;
        $logs = VipPointsLog::find()
                        ->where(['vip_id' => $pay->vip_id, 'points_type' => 1, 'business_id' => $business->id, 'flag' => 1, 'status' => 1])
                        ->andWhere('points>used_points')
                        ->orderBy('id')->all();
        foreach ($logs as $tem_log) {
            if ($used_point == 0)
                break;
            $last_points = $tem_log->points - $tem_log->used_points;
            $tem_used = 0;
            if ($used_point > $last_points) {
                $used_point -= $last_points;
                $tem_used = $last_points;
                $tem_log->used_points += $last_points;
            } else if ($used_point <= $last_points) {
                $tem_log->used_points += $used_point;
                $tem_used = $used_point;
                $used_point = 0;
            }
            if (!$tem_log->save())
                return false;
            $used_log = new VipPointsUsed;
            $used_log->log_id = $tem_log->id;
            $used_log->used_log_id = $log->id;
            $used_log->used_business_id = $log->business_id;
            $used_log->used_points = $tem_used;
            if (!$used_log->save())
                return false;
        }
        $business->deduction_points += $pay->used_point;
        $business->points += $pay->used_point;
        if (!$business->save())
            return FALSE;
        return true;
    }

    /**
     * 通用代币计算
     * @param type $pay
     * @param type $vip_point
     */
    private function commonPoints($pay, &$business) {
        //获取会员在通用的代币数量
        $common_point = VipPoints::find()->where(['vip_id' => $pay->vip_id, 'points_type' => 0])->one();
        if ($common_point) {
            if ($common_point->points > 0) {
                $common_point->points -= $pay->used_point;
                if ($common_point->points < 0)
                    $common_point->points = 0;
            }
            $common_point->pay_at = date('Y-m-d H:i:s');
            if (!$common_point->save())
                return FALSE;

            $log = new VipPointsLog;
            $log->pay_id = $pay->id;
            $log->business_id = 0;
            $log->alliance_id = 0;
            $log->vip_id = $pay->vip_id;
            $log->points_type = 0;
            $log->pre = $business->deduction_pre;
            $log->points = $pay->used_point;
            $log->used_points = 0;
            $log->flag = -1;
            $log->status = 1;
            $log->source = 1;
            if (!$log->save())
                return FALSE;
            $used_point = $pay->used_point;
            $logs = VipPointsLog::find()
                            ->where(['vip_id' => $pay->vip_id, 'points_type' => 0, 'business_id' => 0, 'flag' => 1, 'status' => 1])
                            ->andWhere('points>used_points')
                            ->orderBy('id')->all();
            foreach ($logs as $tem_log) {
                if ($used_point == 0)
                    break;
                $last_points = $tem_log->points - $tem_log->used_points;
                $tem_used = 0;
                if ($used_point > $last_points) {
                    $used_point -= $last_points;
                    $tem_used = $last_points;
                    $tem_log->used_points += $last_points;
                } else if ($used_point <= $last_points) {
                    $tem_log->used_points += $used_point;
                    $tem_used = $used_point;
                    $used_point = 0;
                }
                if (!$tem_log->save())
                    return false;
                $used_log = new VipPointsUsed;
                $used_log->log_id = $tem_log->id;
                $used_log->used_log_id = $log->id;
                $used_log->used_business_id = $business->id;
                $used_log->used_points = $tem_used;
                if (!$used_log->save())
                    return false;
                $business->deduction_points += $pay->used_point;
                if (!$business->save())
                    return FALSE;
            }
            return true;
        }
    }

    /**
     * 联盟代币计算
     * @param type $pay
     */
    private function alliancePoints($pay, &$business, &$vip_point) {
//        $log = new VipPointsLog;
//        $log->pay_id = $pay->id;
//        $log->business_id = $business->id;
//        $log->alliance_id = $pay->alliance_id;
//        $log->vip_id = $pay->vip_id;
//        $log->points_type = 1;
//        $log->pre = $business->deduction_pre;
//        $log->points = $pay->used_point;
//        $log->used_points = 0;
//        $log->flag = -1;
//        $log->status = 1;
//        $log->source = 1;
//        if (!$log->save())
//            return FALSE;
        //先扣除当前商户的代币余额
        $last_points = $pay->used_point;
        if ($vip_point && $vip_point->points > 0) {
            if ($vip_point->points > $pay->used_point) {
                $vip_point->points -= $pay->used_point;
            } else {
                $last_points -= $vip_point->points;
                $vip_point->points = 0;
            }
        }
        //先扣除当前商户的代币余额记录
        $used_point = $pay->used_point;
        $logs = VipPointsLog::find()
                        ->where(['vip_id' => $pay->vip_id, 'points_type' => 1, 'business_id' => $business->id, 'flag' => 1, 'status' => 1])
                        ->andWhere('points>used_points')
                        ->orderBy('id')->all();
        if ($logs) {
            $deduction_points = 0;
            foreach ($logs as $tem_log) {
                if ($used_point == 0)
                    break;
                $tem_last_points = $tem_log->points - $tem_log->used_points;
                $tem_used = 0;
                if ($used_point > $tem_last_points) {
                    $used_point -= $tem_last_points;
                    $tem_used = $tem_last_points;
                    $tem_log->used_points += $tem_last_points;
                } else if ($used_point <= $tem_last_points) {
                    $tem_log->used_points += $used_point;
                    $tem_used = $used_point;
                    $used_point = 0;
                }
                if (!$tem_log->save())
                    return false;
                $log = new VipPointsLog;
                $log->pay_id = $pay->id;
                $log->business_id = $business->id;
                $log->alliance_id = $pay->alliance_id;
                $log->vip_id = $pay->vip_id;
                $log->points_type = 1;
                $log->pre = $business->deduction_pre;
                $log->points = $tem_used;
                $log->used_points = 0;
                $log->flag = -1;
                $log->status = 1;
                $log->source = 1;
                if (!$log->save())
                    return FALSE;

                $used_log = new VipPointsUsed;
                $used_log->log_id = $tem_log->id;
                $used_log->used_log_id = $log->id;
                $used_log->used_business_id = $log->business_id;
                $used_log->used_points = $tem_used;
                if (!$used_log->save())
                    return false;
                $deduction_points += $tem_used;
            }
            $business->points += $tem_used;
            if (!$business->save())
                return FALSE;
            // $business->deduction_points += $deduction_points;
        }
        if ($used_point > 0) {
            //扣除其他联盟的代币余额
            $subPage = (new \yii\db\Query())->select('business_id')->from('alliance_business')->where(['alliance_id' => $pay->alliance_id])->andWhere('vip_points_log.business_id=alliance_business.business_id');
            $other_logs = VipPointsLog::find()
                    ->where(['vip_id' => $pay->vip_id, 'points_type' => 1, 'flag' => 1, 'status' => 1])
                    ->andWhere('points>used_points')
                    ->andWhere(['exists', $subPage])
                    ->all();
            if ($other_logs) {
                $other_business = [];
                foreach ($other_logs as $tem_other_log) {
                    if ($used_point == 0)
                        break;
                    $tem_last_points = $tem_other_log->points - $tem_other_log->used_points;
                    $tem_used = 0;
                    if ($used_point > $tem_last_points) {
                        $used_point -= $tem_last_points;
                        $tem_used = $tem_last_points;
                        $tem_other_log->used_points += $tem_last_points;
                    } else if ($used_point <= $tem_last_points) {
                        $tem_other_log->used_points += $used_point;
                        $tem_used = $used_point;
                        $used_point = 0;
                    }
                    if (!$tem_other_log->save())
                        return false;

                    $log = new VipPointsLog;
                    $log->pay_id = $pay->id;
                    $log->business_id = $tem_other_log->business_id;
                    $log->alliance_id = $pay->alliance_id;
                    $log->vip_id = $pay->vip_id;
                    $log->points_type = 1;
                    $log->pre = $business->deduction_pre;
                    $log->points = $tem_used;
                    $log->used_points = 0;
                    $log->flag = -1;
                    $log->status = 1;
                    $log->source = 1;
                    if (!$log->save())
                        return FALSE;

                    $used_log = new VipPointsUsed;
                    $used_log->log_id = $tem_other_log->id;
                    $used_log->used_log_id = $log->id;
                    $used_log->used_business_id = $business->id;
                    $used_log->used_points = $tem_used;
                    if (!$used_log->save())
                        return false;
                    //记录每家商户扣代币数
                    $tag = FALSE;
                    foreach ($other_business as $k => $v) {
                        if ($tem_other_log->business_id == $other_business[$k]['id'] && $tem_other_log->alliance_id == $other_business[$k]['alliance_id']) {
                            $other_business[$k]['points'] += $tem_used;
                            $tag = true;
                            break;
                        }
                    }
                    if (!$tag) {
                        $other_business[] = ['id' => $tem_other_log->business_id, 'alliance_id' => $tem_other_log->alliance_id, 'points' => $tem_used];
                    }
                }
                $all_other_business = null;
                //承销代币退回发行商
                if ($other_business) {
                    $ids = array_unique(array_column($other_business, 'id'));
                    $all_other_business = Business::find()->where(['id' => $ids])->with(['grade'])->all();
                    foreach ($all_other_business as $tem_business) {
                        foreach ($other_business as $tem_other_business) {
                            if ($tem_business->id == $tem_other_business['id']) {
                                $tem_business->points += $tem_other_business['points'];
                                if (!$tem_business->save())
                                    return false;
                                break;
                            }
                        }
                    }
                    //扣除用户对应商户下的代币
                    $all_vip_business = VipPoints::find()->where(['business_id' => $ids, 'vip_id' => $pay->vip_id])->all();
                    foreach ($all_vip_business as $tem_vip_business) {
                        foreach ($other_business as $tem_other_business) {
                            if ($tem_vip_business->business_id == $tem_other_business['id']) {
                                $tem_vip_business->points -= $tem_other_business['points'];
                                if ($tem_vip_business->points < 0)
                                    $tem_vip_business->points = 0;
                                $tem_vip_business->save();
                                break;
                            }
                        }
                    }
                }

                //计算抽成
                if ($all_other_business != null) {
                    $config = Config::find()->one();
                    foreach ($all_other_business as $tem_business) {
                        foreach ($other_business as $tem_other_business) {
                            if ($tem_business->id == $tem_other_business['id']) {
                                if (!$this->countCommission($tem_business, $business, $tem_other_business, $pay, $config))
                                    return false;
                            }
                        }
                    }
                }
            }
        }
        $business->deduction_points += $pay->used_point;
        if (!$business->save())
            return FALSE;
        return true;
    }

    /**
     * 计算抽成（高等级向低等级抽成）
     */
    private function countCommission($active_business, &$passive_business, $used_points, $pay, $config) {
        Yii::Info('计算抽成');
        Yii::Info($used_points);
        $pre = 0;
        $active_commission = 0;
        $passive_commission = 0;
        Yii::Info($active_business->grade);
        Yii::Info($passive_business);
        //计算两个商户等级的抽成差
        if ($active_business->grade) {
            $active_commission = $active_business->grade->commission;
            if (!$passive_business->grade) {
                $pre = $active_business->grade->commission + $config->same_commission;
            } else if ($active_business->grade->commission > $passive_business->grade->commission) {
                $pre = $config->same_commission + ($active_business->grade->commission - $passive_business->grade->commission);
                $passive_commission = $passive_business->grade->commission;
            } else if ($active_business->grade->commission = $passive_business->grade->commission) {
                $pre = $config->same_commission;
                $passive_commission = $passive_business->grade->commission;
            }
        } else if (!$passive_business->grade) {
            $pre = $config->same_commission;
            $passive_commission =0;
        }
        $dif_points = 0;
        Yii::Info($pre);
        $is_dif = false;
        $dis_commission = $config ? $config->dis_commission : 0;
        $all_points = $this->getCommissionPoints($pay, $used_points['points']);
        //计算是同盟还是异盟（异盟再加上异盟的比例）
        $term = json_decode($pay->term, true);
        if ($term && $term['is_dif'] == 1) {
            $is_dif = true;
            $dif_points = CommonFun::countNum($used_points['points'] * ($dis_commission / 100));
            Yii::Info('异盟');
            YII::Info($dif_points);
        }
        $points = CommonFun::countNum($all_points * ($pre / 100));
        $points += $dif_points;
        if ($points > 0) {
            Yii::info($points);
            //被抽成代币不足则不抽成
            if ($points < $passive_business->points) {
                //扣除承销商户抽成代币
                $passive_business->points -= $points;
                if (!$passive_business->save())
                    return false;
                //计算平台抽成
                $common_points = CommonFun::countNum($points * ($config->common_commission / 100));
                Yii::Info($common_points);
                //新增发行商抽成代币
                $active_points = $points - $common_points;
                $rel = Business::updateAllCounters(['points' => +$active_points], ['id' => $active_business->id]);
                if (!$rel)
                    return false;

                //记录日志
                //发行商
                if (!$this->bussinessPointsLog($active_business->id, $active_points, $pre, $active_commission, 1, $pay->id, $is_dif))
                    return false;
                //承销商
                if (!$this->bussinessPointsLog($passive_business->id, $points, $pre, $passive_commission, -1, $pay->id, $is_dif))
                    return false;
                if ($common_points > 0) {
                    //平台
                    if (!$this->bussinessPointsLog(0, $common_points, $config->common_commission, $config->common_commission, 1, $pay->id, $is_dif))
                        return false;
                }
            }
        }
        return true;
    }

    /**
     * 获取抽成积分（按比例）
     */
    private function getCommissionPoints($pay, $points) {
        if ($pay['used_point'] > 0) {
            return CommonFun::countNum($pay->pay * ($points / $pay['used_point']));
        }
        return 0;
    }

    /**
     * 日志
     */
    private function bussinessPointsLog($business_id, $points, $pre, $cur_pre, $flag, $pay_id, $is_dif) {
        $business_points = new BusinessPoints;
        $business_points->points_type = 2;
        $business_points->business_id = $business_id;
        $business_points->points = $points;
        $business_points->pre = $pre;
        $business_points->cur_pre = $cur_pre;
        $business_points->flag = $flag;
        $business_points->pay_id = $pay_id;
        $business_points->is_dif = $is_dif ? 1 : 0;
        return $business_points->save();
    }

    /**
     * 保留小数点1位（不四舍五入）
     * @param type $points
     * @return type
     */
    private function countNum($points) {
        return floor($number * 10) / 10;
    }

}
