<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'prod');
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../config/bootstrap.php');
require(__DIR__.'/../config/function.php');

$config = require(__DIR__ . '/../config/main.php');
(new yii\web\Application($config))->run();
//try {
//    (new yii\web\Application($config))->run();
//}catch (\InvalidArgumentException $e) {
//  throw new \yii\web\NotFoundHttpException();
//} catch (\app\exception\ImageNotFound $e) {
//    throw new \yii\web\NotFoundHttpException();
//} catch (\yii\web\NotFoundHttpException $e) {
//    throw new \yii\web\NotFoundHttpException();
//} catch (\Exception $e) {
//    Yii::error($e->getMessage());
//    throw new \yii\web\ServerErrorHttpException();
//}
