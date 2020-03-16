<div id="listBox">
    <div class="main-containt">
        <div class="main-title">
            登录日志
        </div>
        <div class="main-page">
            <div class="search-box">
                <input id="txtSearchKey" class="input" type="text" placeholder="请输入姓名/用户名" />
                <input class="btn" id="btnSearch" type="button" value="搜索" />
                <input class="btn btn-gray" id="btnReset" type="button" value="重置" />
            </div>
            <div class="main-list" id="dataListBox">
                <?= Yii::$app->runAction('/setting/page/login-log-list') ?>
            </div>
        </div>
    </div>
</div>
<div id="formBox"></div>
<?= backend\widgets\Script::registerJsFile(); ?>