<?php
$params = array_merge(
    require(__DIR__ . '/params.php')
);
$apiUrl=require(dirname(__DIR__).'/modules/api/config/url.php');
$fooUrl=require(dirname(__DIR__).'/modules/foo/config/url.php');
$hamUrl=require(dirname(__DIR__).'/modules/ham/config/url.php');
$url = array_merge($apiUrl, $fooUrl, $hamUrl);

return [
    'id' => 'image-manager',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log'],
    'language' =>'zh-CN',  //增加此行，默认使用中文
    'modules' => [
        'foo' => [
            'class' => 'application\modules\foo\FooModule',
        ],
        'ham' => [
            'class' => 'application\modules\ham\HamModule',
        ],
        'api' => [
            'class' => 'application\modules\api\apiModule',
        ],
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'cookieValidationKey' => '7If4Pps9MegMaJYlxaJU7prl7tra9fpJ',
            'parsers' => [
                //开启application/json 接受方式
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'image-manager',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,//开启url美化
            'showScriptName' => false,//关闭脚本名显示
            'rules' => $url
        ],
    ],
    'params' => $params,
];
