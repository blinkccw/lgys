<?php

namespace frontend\modules\vip\models;

use Yii;
use common\models\Business;
use common\models\Pay;
use common\models\VipPoints;
use common\models\VipPointsLog;
use common\models\VipPointsUsed;
use common\models\Config;
use common\core\CommonFun;
use frontend\core\wx_pay\WxPayApi;
use frontend\core\wx_pay\WxPayUnifiedOrder;
use frontend\core\wx_pay\WxPayJsApiPay;
use yii\base\Model;

/**
 * 支付页面
 *
 * @author xjx
 */
class PayForm extends Model {

    public $id;
    public $money;
    public $term;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer'],
            [['money'], 'trim'],
            [['money'], 'required'],
            [['money'], 'number'],
            [['term'], 'trim'],
            [['term'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '商户ID',
            'money' => '金额',
            'term' => '条件'
        ];
    }

    /**
     * 支付
     */
    public function save($vip) {
        $business = Business::find()->where(['id' => $this->id])->one();
        $config=Config::find()->one();
        $dis_commission=$config?$config->dis_commission:0;
        if (!$business) {
            $this->addError('save', '商户信息不存在。');
            return FALSE;
        }
        if ($business->status == 0) {
            $this->addError('save', '该商户已经下架。');
            return FALSE;
        }
        if (strlen($business->mch_id) <= 0) {
            $this->addError('save', '该商户没有配置微信支付。');
            return FALSE;
        }
        $pay = new Pay;
        do {
            $pay->no = CommonFun::getNo('pay');
            $tem = Pay::findOne(['no' => $pay->no]);
            if (!$tem)
                break;
        } while (true);
        $pay->vip_id = $vip->id;
        $pay->business_id = $business->id;
        $pay->business_name = $business->name;
        $pay->money = floor($this->money*100)/100;
        $pay_money = $pay->money;
        $used_point = 0;
        $is_dif=0;
        if ($this->term!=null && $vip->points > 0) {
            $term = json_decode($this->term, true);
            if ($term!=null) {
                 $used_point = CommonFun::countNum($pay_money * ($business->deduction_pre / 100));
                //商户代币抵扣
                if ($term['business_id'] > 0 && $term['alliance_id'] == 0) {
                    //获取会员在商户的代币数量
                    $vip_point = VipPoints::find()->where(['business_id' => $business->id, 'vip_id' => $vip->id, 'points_type' => 1])->one();
                    if ($vip_point && $vip_point->points > 0) {
                        if ($vip_point->points < $used_point) {
                            $used_point = round($vip_point->points,1);
                        }
                    } else {
                        $used_point = 0;
                    }
                }
                //通用代币抵扣
                if ($term['business_id'] == 0 && $term['alliance_id'] == 0) {
                    //获取会员在通用的代币数量
                    $vip_point = VipPoints::find()->where(['vip_id' => $vip->id, 'points_type' => 0])->one();
                    if ($vip_point && $vip_point->points > 0) {
                        if ($vip_point->points < $used_point) {
                            $used_point = round($vip_point->points,1);
                        }
                    } else {
                        $used_point = 0;
                    }
                }
                //联盟代币抵扣
                if ($term['business_id'] == 0 && $term['alliance_id'] > 0) {
                    $subPage = (new \yii\db\Query())->select('business_id')->from('alliance_business')->where(['alliance_id' => $term['alliance_id']])->andWhere('vip_points.business_id=alliance_business.business_id');
                    $all_points = VipPoints::find()->where(['vip_id' => $vip->id, 'points_type' => 1])->andWhere(['exists', $subPage])->sum('points');
                    if ($all_points != null && $all_points > 0) {
                        $all_points=round($all_points,1);
                        if ($all_points < $used_point) {
                            $used_point = $all_points;
                        }
                    } else {
                        $used_point = 0;
                    }
                    $pay->alliance_id = $term['alliance_id'];
                    $pay->alliance_name = $term['name'];
                    $is_dif=$term['is_dif'];
                    
                }
            }
        }
        $used_point=CommonFun::countNum($used_point);
        $pay->pay = $pay_money - $used_point;
        //是否异盟
        if($is_dif==1){
            $pay->pay+=CommonFun::countTwoNum($used_point * ($dis_commission / 100));
        }
        Yii::info($pay->pay);
        $pay->point = CommonFun::countNum($pay->pay * ($business->exchange_pre / 100));
        if ($pay->point >= $business->points)
            $pay->point = $business->points;
        $pay->used_point = $used_point;
        $pay->term = $this->term;
        if (!$pay->save())
            return false;
        //$pay->status = 1;
        //存在则处理
//        $transaction = Yii::$app->db->beginTransaction();
//        try {
//            if (!$pay->save())
//                return false;
//            if (!$this->doPay($pay, $business, $vip))
//                return false;
//            $transaction->commit();
//            return ['id' => $pay->id];
//        } catch (Exception $e) {
//            $transaction->rollBack();
//            return false;
//        }
        if ($pay->pay > 0) {
            $rel = $this->getWxPrepayId($pay, $vip,$business);
            if ($rel === false) {
                $this->addError('save', '微信支付请求失败。');
                return FALSE;
            }
            $rel['id'] = $pay->id;
            return $rel;
        } else {
            return ['id' => $pay->id];
        }
        return FALSE;
    }

    /**
     * 
     * @param type $pay
     */
    private function doPay($pay, $business, $vip) {
//        $vip = Vip::find()->where(['id' => $pay->vip_id])->one();
//        if (!$vip)
//            return FALSE;
        $vip_point = VipPoints::find()->where(['business_id' => $business->id, 'vip_id' => $vip->id, 'points_type' => 1])->one();
        $alliance_id = 0;
        $alliance_name = '';
        //存在则处理
        //计算代币使用记录
        if ($pay->used_point > 0) {
            $term = json_decode($pay->term, true);
            if ($term) {
                //商户代币抵扣
                if ($term['business_id'] > 0 && $term['alliance_id'] == 0) {
                    if (!$this->businessPoints($pay, $business, $vip_point))
                        return FALSE;
                    $business->deduction_points += $pay->used_point;
                }
                //通用代币抵扣
                if ($term['business_id'] == 0 && $term['alliance_id'] == 0) {
                    if (!$this->commonPoints($pay, $business))
                        return FALSE;
                }
                //联盟代币抵扣
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
        $business->points += $pay->used_point;
        $business->total_points += $pay->used_point;
        if (!$business->save())
            return FALSE;
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
        return true;
    }

    /**
     * 商户代币计算
     * @param type $pay
     * @param type $vip_point
     */
    private function businessPoints($pay, $business, &$vip_point) {
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
        return true;
    }

    /**
     * 通用代币计算
     * @param type $pay
     * @param type $vip_point
     */
    private function commonPoints($pay, $business) {
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
            }
            return true;
        }
    }

    /**
     * 联盟代币计算
     * @param type $pay
     */
    private function alliancePoints($pay, &$business, &$vip_point) {
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
                $used_log = new VipPointsUsed;
                $used_log->log_id = $tem_log->id;
                $used_log->used_log_id = $log->id;
                $used_log->used_business_id = $log->business_id;
                $used_log->used_points = $tem_used;
                if (!$used_log->save())
                    return false;
                $deduction_points += $tem_used;
            }
            $business->deduction_points += $deduction_points;
        }
        if ($used_point > 0) {
            //扣除其他联盟的代币余额
            $subPage = (new \yii\db\Query())->select('business_id')->from('alliance_business')->where(['alliance_id' => $pay->alliance_id])->andWhere('vip_points_log.business_id=alliance_business.business_id');
            $other_logs = VipPointsLog::find()
                            ->where(['vip_id' => $pay->vip_id, 'points_type' => 1, 'flag' => 1, 'status' => 1])
                            ->andWhere('points>used_points')
                            ->andWhere(['exists', $subPage])->all();
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
                    $used_log = new VipPointsUsed;
                    $used_log->log_id = $tem_other_log->id;
                    $used_log->used_log_id = $log->id;
                    $used_log->used_business_id = $log->business_id;
                    $used_log->used_points = $tem_used;
                    if (!$used_log->save())
                        return false;
                    //记录每家商户扣代币数
                    $tag = FALSE;
                    foreach ($other_business as $k => $v) {
                        if ($tem_other_log->business_id == $other_business[$k]['id']) {
                            $other_business[$k]['points'] += $tem_used;
                            $tag = true;
                            break;
                        }
                    }
                    if (!$tag) {
                        $other_business[] = ['id' => $tem_other_log->business_id, 'points' => $tem_used];
                    }
                }

                if ($other_business) {
                    $ids = array_column($other_business, 'id');
                    $all_other_business = Business::find()->where(['id' => $ids])->all();
                    foreach ($all_other_business as $tem_business) {
                        foreach ($other_business as $tem_other_business) {
                            if ($tem_business->id == $tem_other_business['id']) {
                                $tem_business->deduction_points += $tem_other_business['points'];
                                $tem_business->save();
                                break;
                            }
                        }
                    }
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
            }
        }
        return true;
    }

    /**
     * 获取微信支付（PrepayId）
     * @return type
     */
    private function getWxPrepayId($pay, $vip,$business) {
        $config = Yii::$app->params['wxPayConfig'];
        $config['APPID'] = Yii::$app->params['p_wx_appid'];
        $config['MCHID'] = Yii::$app->params['mch_id'];
        $config['SUBAPPID'] = Yii::$app->params['wx_appid'];
        $config['SUBMCHID'] = $business->mch_id;
        $config['KEY'] = Yii::$app->params['pay_key'];
        $config['APPSECRET'] = Yii::$app->params['wx_appsecret'];
        $notify_url = Yii::$app->params['wx_notify_url'];
        $body = $pay->business_name;
        $inputObj = new WxPayUnifiedOrder;
        $inputObj->SetBody($body);
        $inputObj->SetAttach($body);
        $inputObj->SetOut_trade_no($pay->no);
        $inputObj->SetTotal_fee(intval($pay->pay * 100));
        //$inputObj->SetOpenid($vip->open_id);
        $inputObj->SetSubOpenid($vip->open_id);
        $inputObj->SetTrade_type('JSAPI');
        $result = WxPayApi::unifiedOrder($config, $inputObj, $notify_url);

        if (!array_key_exists("appid", $result) ||
                !array_key_exists("mch_id", $result) ||
                !array_key_exists("prepay_id", $result)) {
            Yii::error($result);
            return false;
            // return $this->errorJson('支付接口配置信息不正确。');
        }
        //  Yii::info($result);
        $jsapi = new WxPayJsApiPay;
        $jsapi->SetAppid($result["sub_appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $result['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign($config['KEY']));
        $parameters = $jsapi->GetValues();
        return ['parms' => $parameters];
    }

}
