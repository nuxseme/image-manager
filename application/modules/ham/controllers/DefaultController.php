<?php

namespace application\modules\ham\controllers;

use yii\web\Controller;

/**
 * Default controller for the `ham` module
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
        echo $this->getUniqueId();
        //return $this->render('index');
    }
}
