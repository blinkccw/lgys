<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\MainAsset;
use yii\helpers\Html;

MainAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head><!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="renderer" content="webkit" />
            <?= Html::csrfMetaTags() ?>
            <title><?= Html::encode($this->title) ?></title>
            <?php $this->head() ?>
        </head>
        <body>
            <div class="containt">
                <div class="main-top">
                    <div class="logo">运营管理平台</div>
                    <ul>
                        <li><?= Html::encode($this->params['username']) ?></li>
                        <li onclick="window.location.href = '/site/logout'">退出</li>
                    </ul>
                </div>
                <div class="main-left" id="mainLeft">
                    <div class="menu" id="leftMenus">
                        <ul>
                            <li class="cur"><i class="iconfont icon-home"></i><div>首页</div></li>
                            <li><i class="iconfont icon-shop"></i><div>商户</div></li>
                            <li><i class="iconfont icon-vip"></i><div>用户</div></li>
                            <li><i class="iconfont icon-distributor"></i><div>联盟</div></li>
                            <li><i class="iconfont icon-report"></i><div>记录</div></li>
                            <li><i class="iconfont icon-setting"></i><div>设置</div></li>
                        </ul>
                    </div>
                    <div class="sub-menu" id="leftSubMenus">
                        <div class="sub-menu-box">
                        </div>
                    </div>
                </div>
                <div class="main-right" id="mainContent">
                    <?php $this->beginBody() ?>
                    <?= $content ?>
                    <?php $this->endBody() ?>
                </div>
            </div>
            <script>
                var SITECONFIG = {'IMG_URL': '<?= Yii::$app->params['WEB_URL'] ?>'};
            </script>
        </body>
    </html>
    <?php $this->endPage() ?>
