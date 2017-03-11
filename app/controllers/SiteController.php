<?php
namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $token = $_POST['token'] ?? '';
        if(empty($token)){
            return  $this->renderPartial('index',['message' => '请输入token']);
        }else {
            if($token != Yii::$app->params['token']) {
                return  $this->renderPartial('index',['message' => 'token错误请重新输入']);
            }
            //校验通过 设置session
            $session = Yii::$app->session;
            $session['token.value'] = Yii::$app->params['token'];
            if(isset($_GET['redirectUrl'])) {
                return $this->redirect($_GET['redirectUrl']);
            }
            exit('token校验成功');
        }
    }

    public function actionMyError()
    {
        throw new NotFoundHttpException();
    }
}
