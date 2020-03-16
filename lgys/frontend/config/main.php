<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'main' => [
            'class' => 'frontend\modules\main\Module',
        ],
        'vip' => [
            'class' => 'frontend\modules\vip\Module',
        ],
        'business' => [
            'class' => 'frontend\modules\business\Module',
        ],
        'notice' => [
            'class' => 'frontend\modules\notice\Module',
        ],
        'alliance' => [
            'class' => 'frontend\modules\alliance\Module',
        ],
    ],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'zh-CN',
    'components' => [
        'request' => [
            'csrfParam' => '_auth',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-app', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
