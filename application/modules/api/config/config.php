<?php
return [
    'components' => [
        // list of component configurations
        'urlManager' => [
            'class' => ' yii\web\UrlManager',
            'enablePrettyUrl' => true,//开启url美化
            'showScriptName' => false,//关闭脚本名显示
            'rules' => [
                //'<controller:\w+>/<id:\d+>' =>'<controller>/view',
                //"<controller:\w+>/<action:\w+>"=>"<controller>/<action>",
                //指定size 缩略   兼容旧版本url
                'imaging/imaging/product/<sku:.+>/<w:\d+>-<h:\d+>-<p:\d+>/<image_name:.+>' => 'image/thumb/fetch',
                //原图 缩略  兼容旧版本url
                'imaging/imaging/product/<sku:.+>/<image_name:.+>' => 'image/thumb/fetch',
                //商品主图展示
                'product/showImages' => 'image/show/show-images',
                //根据 sku 批量获取商品图片数据
                'product/batchFetchImages' => 'api/image/batch-fetch-images',
                //ERP 上传商品主图
                'uploadImage/uploadImageToOnline' => 'api/upload/handle',
                //商品详情上传视图
                'upload/uploadImage' => 'image/upload/index',
                //上传图片 缩略
                'image/upload/<w:\d+>-<h:\d+>-<p:\d+>-<adapt:\d+>/<image_name:.+>' => 'image/show/upload',

                //图片查看
                'images/uploads/<file:.+>' => 'image/show/index',

                //图片查看接口
                'images/<file:.+>' => 'api/image/show',


                #---------------webpos---------------#
                //webpos 获取缩略图
                'webpos/<uuid:.+>/<sku:.+>/<w:\d+>-<h:\d+>-<p:\d+>/<image_name:.+>' => 'api/webpos/thumb/handle',
                //webpos  查看图片
                'webpos/<uuid:.+>/<sku:.+>/<image_name:.+>' => 'api/webpos/image/render',
                'api/<sku:.+>/<uuid:\d+>'=>'api/index/index'
            ]
        ],
    ],
    'params' => [
        // list of parameters
        'aa' => 'bb'
    ],
];