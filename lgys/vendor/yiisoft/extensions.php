<?php

$vendorDir = dirname(__DIR__);

return array (
  'yiisoft/yii2-swiftmailer' => 
  array (
    'name' => 'yiisoft/yii2-swiftmailer',
    'version' => '2.1.2.0',
    'alias' => 
    array (
      '@yii/swiftmailer' => $vendorDir . '/yiisoft/yii2-swiftmailer/src',
    ),
  ),
  'yiisoft/yii2-bootstrap' => 
  array (
    'name' => 'yiisoft/yii2-bootstrap',
    'version' => '2.0.9.0',
    'alias' => 
    array (
      '@yii/bootstrap' => $vendorDir . '/yiisoft/yii2-bootstrap/src',
    ),
  ),
  'yiisoft/yii2-debug' => 
  array (
    'name' => 'yiisoft/yii2-debug',
    'version' => '2.0.14.0',
    'alias' => 
    array (
      '@yii/debug' => $vendorDir . '/yiisoft/yii2-debug/src',
    ),
  ),
  'yiisoft/yii2-gii' => 
  array (
    'name' => 'yiisoft/yii2-gii',
    'version' => '2.0.8.0',
    'alias' => 
    array (
      '@yii/gii' => $vendorDir . '/yiisoft/yii2-gii/src',
    ),
  ),
  'yiisoft/yii2-faker' => 
  array (
    'name' => 'yiisoft/yii2-faker',
    'version' => '2.0.4.0',
    'alias' => 
    array (
      '@yii/faker' => $vendorDir . '/yiisoft/yii2-faker',
    ),
  ),
    'yiisoft/yii2-redis' =>
    array(
        'name' => 'yiisoft/yii2-redis',
        'version' => '2.0.0.0',
        'alias' =>
        array(
            '@yii/redis' => $vendorDir . '/yiisoft/yii2-redis',
        ),
    ),
    'yiisoft/yii2-qrcode' =>
    array(
        'name' => 'yiisoft/yii2-qrcode',
        'version' => '1.0.2.0',
        'alias' =>
        array(
            '@dosamigos/qrcode' => $vendorDir . '/yiisoft/yii2-qrcode',
        ),
    ),
      'yiisoft/yii2-imagine' =>
    array(
        'name' => 'yiisoft/yii2-imagine',
        'version' => '2.0.4.0',
        'alias' =>
        array(
            '@yii/imagine' => $vendorDir . '/yiisoft/yii2-imagine',
        ),
    ),
    'yiisoft/Imagine' =>
    array(
        'name' => 'yiisoft/Imagine',
        'version' => '5.0.0.0',
        'alias' =>
        array(
            '@Imagine' => $vendorDir . '/yiisoft/yii2-imagine/imagine',
        ),
    ),
);
