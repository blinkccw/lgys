<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
     //微信支付配置
    'wxPayConfig' => [
        //APPID：绑定支付的APPID（必须配置，开户邮件中可查看
        'APPID' => '',
        //商户号（必须配置，开户邮件中可查看）
        'MCHID' => '',
        //商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
        'KEY' => '',
        //APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
        'APPSECRET' => '',
        //TODO：设置商户证书路径
        'SSLCERT_PATH' => '/htdocs/www/paopaoyan/frontend/cert/apiclient_cert.pem',
        'SSLKEY_PATH' => '/htdocs/www/paopaoyan/frontend/cert/apiclient_key.pem',
        //curl代理设置
        'CURL_PROXY_HOST' => "0.0.0.0",
        'CURL_PROXY_PORT' => 0,
        //上报信息配置
        'REPORT_LEVENL' => 1
    ],
];
