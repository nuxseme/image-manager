<?php


namespace app\controllers;


use yii\web\Controller;
use yii\web\Response;

class TestController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\HttpCache',
                'only' => ['index'],
                //此处可设置过期时间
                'cacheControlHeader' => 'public, max-age=100',
                'lastModified' => function ($action, $params) {
                    $img = '/data/tomtop-product-online-image-0.1.0/webpos/I/4/I1234/122343651326561/1234.png';
                    return $this->getFileTimeLastModifyTime($img);
                },
            ],
        ];
    }

    public function actionRender()
    {
        //90-110ms
        $img = '/data/tomtop-product-online-image-0.1.0/webpos/I/4/I1234/122343651326561/1234.png';
        ob_start();
        readfile($img);
        $response = \Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/png');
        $response->format = Response::FORMAT_RAW;
        $response->content = ob_get_clean();
        return $response->send();
    }

    public function actionRender2()
    {
        //80-100
        $img = '/data/tomtop-product-online-image-0.1.0/webpos/I/4/I1234/122343651326561/1234.png';

        $response = \Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/png');
        $response->format = Response::FORMAT_RAW;
        if ( !is_resource($response->stream = fopen($img, 'r')) )
            throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
        return $response->send();
    }

    public function actionRender1()
    {
        //80-100ms
        $img = '/data/tomtop-product-online-image-0.1.0/webpos/I/4/I1234/122343651326561/1234.png';
        $time = $this->getFileTimeLastModifyTime($img);
        header('Content-type: image/png');
        echo file_get_contents($img);
    }

    public function getFileTimeLastModifyTime($img)
    {
        $last_modify_time = filemtime($img);
        return $last_modify_time;
    }

    public function actionIndex()
    {
        return $this->renderPartial('index.php');
    }
}
