<?php

namespace application\modules\foo\controllers;

use yii\web\Controller;

/**
 * Default controller for the `foo` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        echo 'hello';
        //return $this->render('index');
        print_r(\Yii::$app->params);
        //print_r(\Yii::$app);
        print_r(\Yii::$app->controller->module->params);
    }
}
