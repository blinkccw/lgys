<?php


namespace frontend\core\wx_pay;

/**
 * Description of WxPayException
 *
 * @author Administrator
 */
class WxPayException extends \yii\base\Exception{

    public function errorMessage() {
        return $this->getMessage();
    }

}
