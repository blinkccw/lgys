<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex() {
        $cookies = Yii::$app->request->cookies;
        $username = $cookies->get('username', '');
        return $this->render('index', ['username' => $username]);
    }

    /**
     * H5页面
     * @return type
     */
    public function actionH5() {
        return $this->render('h5');
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTest() {
        $number=3.58;
        echo floor($number*10)/10;
        die();
//        $poster_Image = new \Imagick;
//        $poster_Image->setformat('png');
//        $poster_Image->newimage(598, 666, '#fff');
//        $img = new \Imagick(Yii::getAlias('@frontend/web/images/') . 'timg.jpg');
//        //缩小商户图片
//        $img->thumbnailimage(558, 428);
//        $poster_Image->compositeimage($img, \Imagick::COMPOSITE_OVER, 20, 20);
//        //商户名称
//        $text = "王品台塑牛排";
//        $style['font_size'] = 32;
//        $style['font'] = Yii::getAlias("@frontend/web/css/msyh.ttf");
//        $style['fill_color'] = '#333333';
//        $font_title = new \Imagick();
//        $attr = $this->getWidth($font_title, $style, $text);
//        $h = 80;
//        $font_title->newImage($attr['textWidth'], $h, 'transparent', 'png');
//        $this->add_text($font_title, $text, 0, $attr['textHeight'], 0, $style);
//        $poster_Image->compositeImage($font_title, \Imagick::COMPOSITE_OVER, 40, 470);
//
//        $tag1 = "你的好友：CORSTAR";
//        $tag2 = "正在发起该商户的代币聚合。";
//        $tag3 = "赶快来支持一下吧！";
//        $style['font_size'] = 24;
//        $style['fill_color'] = '#8a8a8a';
//
//        $h = 45;
//        $font_tag = new \Imagick();
//        $attr = $this->getWidth($font_tag, $style, $tag1);
//        $font_tag->newImage($attr['textWidth'], $h, 'transparent', 'png');
//        $this->add_text($font_tag, $tag1, 0, $attr['textHeight'], 0, $style);
//        $poster_Image->compositeImage($font_tag, \Imagick::COMPOSITE_OVER, 40, 540);
//
//        $font_tag = new \Imagick();
//        $attr = $this->getWidth($font_tag, $style, $tag2);
//        $font_tag->newImage($attr['textWidth'], $h, 'transparent', 'png');
//        $this->add_text($font_tag, $tag2, 0, $attr['textHeight'], 0, $style);
//        $poster_Image->compositeImage($font_tag, \Imagick::COMPOSITE_OVER, 40, 570);
//
//        $font_tag = new \Imagick();
//        $attr = $this->getWidth($font_tag, $style, $tag3);
//        $font_tag->newImage($attr['textWidth'], $h, 'transparent', 'png');
//        $this->add_text($font_tag, $tag3, 0, $attr['textHeight'], 0, $style);
//        $poster_Image->compositeImage($font_tag, \Imagick::COMPOSITE_OVER, 40, 600);
//
//
//        $font_title->destroy();
//        $img->destroy();
//        header('Content-type:' . strtolower($poster_Image->getformat()));
//        echo $poster_Image->getimagesblob();
//        die();
    }

    /**
     * 获取文字大小
     * @param type $imagick
     * @param type $style
     * @param type $text
     * @return type
     */
    protected function getWidth(&$imagick, $style, $text) {
        $draw = new \ImagickDraw();
        if (isset($style ['font']))
            $draw->setFont($style ['font']);
        if (isset($style ['font_size']))
            $draw->setFontSize($style ['font_size']);
        $draw->settextencoding('UTF-8');
        return $imagick->queryFontMetrics($draw, $text);
    }

    // 添加水印文字
    public function add_text(&$imagick, $text, $x = 0, $y = 0, $angle = 0, $style = array()) {
        $draw = new \ImagickDraw();
        if (isset($style ['font']))
            $draw->setFont($style ['font']);
        if (isset($style ['font_size']))
            $draw->setFontSize($style ['font_size']);
        if (isset($style ['fill_color']))
            $draw->setFillColor($style ['fill_color']);
        if (isset($style ['under_color']))
            $draw->setTextUnderColor($style ['under_color']);
        if (isset($style ['font_family']))
            $draw->setfontfamily($style ['font_family']);
        if (isset($style ['font']))
            $draw->setfont($style ['font']);
        $draw->settextencoding('UTF-8');
        $imagick->annotateImage($draw, $x, $y, $angle, $text);
    }

}
