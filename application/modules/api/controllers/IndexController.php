<?php

namespace application\modules\api\controllers;

use image\Image;
use image\Thumb;
use image\thumb\GD;
use yii\web\Controller;

/**
 * Default controller for the `foo` module
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        echo 'hello';
        print_r($_GET);
        print_r(\Yii::$app->controller->module->params);
        //return $this->render('index');
        //print_r(\Yii::$app->params);
        //print_r(\Yii::$app);
        //print_r(\Yii::$app->controller->module->params);
    }
    public function actionTest()
    {
        $origin = __DIR__.'/cat.png';
        $img = new Image($origin);
        print_r($img->getInfo());
    }
}
