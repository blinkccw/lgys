<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'main' => [
            'class' => 'backend\modules\main\Module',
        ],
        'business' => [
            'class' => 'backend\modules\business\Module',
        ],
        'vip' => [
            'class' => 'backend\modules\vip\Module',
        ],
        'alliance' => [
            'class' => 'backend\modules\alliance\Module',
        ],
        'report' => [
            'class' => 'backend\modules\report\Module',
        ],
        'setting' => [
            'class' => 'backend\modules\setting\Module',
        ],
    ],
    'language' => 'zh-CN',
    'components' => [
        'request' => [
            'csrfParam' => '_auth',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-cp', 'httpOnly' => true],
            'loginUrl' => ['/site/logout']
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'db-cp',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];
