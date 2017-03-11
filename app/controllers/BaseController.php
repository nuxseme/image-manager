<?php


namespace app\controllers;

use yii\web\Controller;
use Yii;
use yii\helpers\Url;
class BaseController extends Controller
{
    public function init()
    {
        $token =  Yii::$app->session->get('token.value');
        if($token != Yii::$app->params['token']) {
            return $this->redirect(Url::toRoute(['/site/index','redirectUrl' => Yii::$app->request->getUrl()]));
        }
    }
}