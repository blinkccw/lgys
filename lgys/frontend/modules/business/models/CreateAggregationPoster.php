<?php

namespace frontend\modules\business\models;

use Yii;
use common\models\VipAggregation;
use common\core\WxBusiness;
use yii\base\Model;

/**
 * 聚合表单
 */
class CreateAggregationPoster extends Model {

    public $id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'trim'],
            [['id'], 'required'],
            [['id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'id'
        ];
    }

    /**
     * 保存
     */
    public function create() {
        $aggregation = VipAggregation::find()
                        ->where(['id' => $this->id])
                        ->with(['vip', 'business', 'business.face'])
                        ->asArray()->one();
        if (!$aggregation) {
            $this->addError('save', '聚合信息已经不存在。');
            return FALSE;
        }
        if (!$aggregation['business']) {
            $this->addError('save', '商户信息已经不存在。');
            return FALSE;
        }
        //生成二维码
        $qr_dir = Yii::getAlias('@frontend/web/qr/s');
        if (!file_exists($qr_dir))
            mkdir($qr_dir, 0777, true);
        $wx = new WxBusiness;
        $qr_file = $qr_dir . '/qr_' . $aggregation['id'] . '.png';
        if (!file_exists($qr_file)) {
            $wx->createQr($qr_file, $aggregation['id'], 'pages/business/aggregation/aggregation');
        }

        $poster_Image = new \Imagick;
        $poster_Image->setformat('png');
        $poster_Image->newimage(598, 666, '#fff');

        if ($aggregation['business']['face']) {
            $img = new \Imagick(Yii::getAlias('@frontend/web') . $aggregation['business']['face']['img_path']);
            //缩小商户图片
            $img->thumbnailimage(558, 428);
            $poster_Image->compositeimage($img, \Imagick::COMPOSITE_OVER, 20, 20);
        }

        //商户名称
        $text = $aggregation['business']['name'];
        $style['font_size'] = 32;
        $style['font'] = Yii::getAlias("@frontend/web/css/msyh.ttf");
        $style['fill_color'] = '#333333';
        $font_title = new \Imagick();
        $attr = $this->getWidth($font_title, $style, $text);
        $h = 80;
        $font_title->newImage($attr['textWidth'], $h, 'transparent', 'png');
        $this->add_text($font_title, $text, 0, $attr['textHeight'], 0, $style);
        $poster_Image->compositeImage($font_title, \Imagick::COMPOSITE_OVER, 40, 470);

        $tag1 = "你的好友：" . ($aggregation['vip']['name'] ? $aggregation['vip']['name'] : '无');
        $tag2 = "正在发起该商户的代币聚合。";
        $tag3 = "赶快来支持一下吧！";
        $style['font_size'] = 24;
        $style['fill_color'] = '#8a8a8a';

        $h = 45;
        $font_tag = new \Imagick();
        $attr = $this->getWidth($font_tag, $style, $tag1);
        $font_tag->newImage($attr['textWidth'], $h, 'transparent', 'png');
        $this->add_text($font_tag, $tag1, 0, $attr['textHeight'], 0, $style);
        $poster_Image->compositeImage($font_tag, \Imagick::COMPOSITE_OVER, 40, 540);

        $font_tag = new \Imagick();
        $attr = $this->getWidth($font_tag, $style, $tag2);
        $font_tag->newImage($attr['textWidth'], $h, 'transparent', 'png');
        $this->add_text($font_tag, $tag2, 0, $attr['textHeight'], 0, $style);
        $poster_Image->compositeImage($font_tag, \Imagick::COMPOSITE_OVER, 40, 570);

        $font_tag = new \Imagick();
        $attr = $this->getWidth($font_tag, $style, $tag3);
        $font_tag->newImage($attr['textWidth'], $h, 'transparent', 'png');
        $this->add_text($font_tag, $tag3, 0, $attr['textHeight'], 0, $style);
        $poster_Image->compositeImage($font_tag, \Imagick::COMPOSITE_OVER, 40, 600);

        if (file_exists($qr_file)) {
            $qr_img = new \Imagick($qr_file);
            //缩小图片
            $qr_img->thumbnailimage(162, 162);
            $poster_Image->compositeimage($qr_img, \Imagick::COMPOSITE_OVER, 410, 480);
        }else{
             $qr_img = new \Imagick(Yii::getAlias('@frontend/web/qr/qr.jpg'));
            //缩小图片
            $qr_img->thumbnailimage(162, 162);
            $poster_Image->compositeimage($qr_img, \Imagick::COMPOSITE_OVER, 410, 480);
        }
        $img_path = Yii::getAlias("@frontend/web/share/" . $aggregation['id'] . '.png');
        $rel = $poster_Image->writeimage($img_path);

        $font_tag->destroy();
        $font_title->destroy();
        $img->destroy();
        $poster_Image->destroy();

        if ($rel)
            return Yii::$app->params['WEB_URL'] . "/share/" . $aggregation['id'] . '.png';
        return false;
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
