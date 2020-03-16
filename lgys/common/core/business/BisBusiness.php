<?php

namespace common\core\business;

use Yii;
use common\models\BusinessGrade;
use common\models\Business;
use common\models\VipPoints;
/**
 *商户操作类
 *
 * @author xjx
 */
class BisBusiness {
    /**
     * 验证是否升级
     */
    public function upgradeGrade($business){
        $grades=BusinessGrade::find()->orderBy('vip_num desc')->all();
        if($grades){
            $vip_num= VipPoints::find()->where(['business_id'=>$business->id])->count('id');
            Yii::info($vip_num);
            foreach ($grades as $grade){
                if($business->grade_id==$grade->id){
                    break;
                }
                if($vip_num>=$grade->vip_num){
                    $business->grade_id=$grade->id;
                    $business->save();
                    break;
                }
            }
        }
    }
}
