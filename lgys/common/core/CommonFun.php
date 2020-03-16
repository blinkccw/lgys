<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\core;

use Yii;

/**
 * 公共方法
 *
 * @author xiaojx
 */
class CommonFun {

    /**
     * 是否手机访问
     * @return booleanj
     */
    public static function isMobile() {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;

        //此条摘自TPM智能切换模板引擎，适合TPM开发
        if (isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
            return true;
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
        //判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
            ];
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 是否微信访问
     * @return boolean
     */
    public static function isWx() {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($ua, 'MicroMessenger') == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 生成GUID（UUID）
     * @access public
     * @return string
     * @author knight
     */
    public static function createGuid() {
        if (function_exists('com_create_guid')) {
            return strtolower(com_create_guid());
        } else {
            mt_srand((double) microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12); // "}"
            return strtolower($uuid);
        }
    }

    /**
     * 将XML转为array
     * @param type $xml
     * @return type
     */
    public static function xmlToArray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    /**
     * 获取周
     * @param type $date
     */
    public static function getWeek($date) {
        $w = date('w', strtotime($date));
        switch ($w) {
            case 1:
                return '周一';
            case 2:
                return '周二';
            case 3:
                return '周三';
            case 4:
                return '周四';
            case 5:
                return '周五';
            case 6:
                return '周六';
            case 0:
                return '周日';
        }
        return '';
    }

    /**
     * 
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public static function getNonceNum($length = 32) {
        $chars = "01234567889";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取随机数
     * @return type
     */
    public static function getRandom($length = 8) {
        $random = strtolower(Yii::$app->security->generateRandomString());
        $random = str_replace('-', '', $random);
        $random = str_replace('_', '', $random);
        return substr($random, 0, $length);
    }

    /**
     * 获取毫秒级别的时间戳
     */
    public static function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    /**
     * 获取年
     * @return type
     */
    public static function getYearList() {
        $begin_year = 2018;
        $year = date('Y');
        for ($i = 0; $i <= $year - $begin_year; $i++) {
            $year_list[] = ['year' => $begin_year + $i];
        }
        return $year_list;
    }

    /**
     * 保护名称
     */
    public static function getStarName($name) {
        $len = mb_strlen($name, 'utf-8');
        if ($len == 2) {
            return mb_substr($name, 0, 1, 'utf-8') . '*';
        } else if ($len > 2) {
            $tem = mb_substr($name, 0, 1, 'utf-8');
            for ($i = 1; $i < $len - 1; $i++) {
                $tem .= '*';
            }
            $tem .= mb_substr($name, $len - 1, 1, 'utf-8');
            $name = $tem;
        }
        return $name;
    }

    /**
     * 转义php代码
     * @param type $str
     * @return type
     */
    public static function clearPhpCode($str) {
        return str_replace(['<?', '<%', '<?php', '<%php'], [htmlspecialchars('<?'), htmlspecialchars('<%'), htmlspecialchars('<?php'), htmlspecialchars('<%php')], $str);
    }

    /**
     * 获取当前网页
     * @return type
     */
    public static function getWebUrl() {
        return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * 获取站点路径
     */
    public static function getSitePath($random) {
        $md5 = md5($random);
        return '/' . substr($md5, 0, 1) . '/' . substr($md5, 1, 1) . '/' . $random;
    }

    public static function createVipNo(){
         return '8'.CommonFun::getNonceNum(7);
    }

    /**
     * 获取订单号
     */
    public static function getNo($type) {
        $no = 0;
        switch ($type) {
            case 'vip':
                $no = 8;
                break;
            case 'pay':
                $no = 6;
                break;
        }
        $millisecond = CommonFun::getMillisecond();
        $no .= substr($millisecond, -8);
        $no .= CommonFun::getNonceNum(5);
        return $no;
    }
  /**
     * 计算两个经维度的距离
     * @param type $lat1
     * @param type $lng1
     * @param type $lat2
     * @param type $lng2
     * @return type
     */
    public static function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit = 2, $decimal = 2) {
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI / 180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if ($unit == 2) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
    
        /**
     * 保留小数点1位（不四舍五入）
     * @param type $number
     * @return type
     */
    public static function countNum($number){
        return floor($number*10)/10;
    }
    
        
        /**
     * 保留小数点1位（不四舍五入）
     * @param type $number
     * @return type
     */
    public static function countTwoNum($number){
        return floor($number*100)/100;
    }
}
