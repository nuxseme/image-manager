<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\controllers\api;

use yii\web\Controller;
use Yii;
use yii\web\Response;

/**
 * Class BaseController
 * @package app\controllers\api
 */
class BaseController  extends Controller
{
    public function beforeAction($action)
    {
        //设置 api 接口响应格式为 application/json
        return Yii::$app->response->format = Response::FORMAT_JSON;
    }

}