<?php
/**
 * @link http://www.tomtop.com/
 * @copyright Copyright (c) 2016 TOMTOP
 * @license http://www.tomtop.com/license/
 *
 */

namespace app\controllers\api\webpos;


use app\models\image\ImageEntity;
use app\services\image\SkuService;
use Yii;
use yii\web\Controller;

class ImageController extends Controller
{
    /**
     *webpos 显示原图
     */
    public function actionRender()
    {
        $sku = $_GET['sku'];
        $file_name = $_GET['image_name'];
        $uuid = $_GET['uuid'];
        $webpos_img_base_dir = Yii::$app->params['webpos_root'];
        $sku_path = SkuService::createPath($sku);
        $file_path = $webpos_img_base_dir.$sku_path.$sku.'/'.$uuid.'/'.$file_name;
        $fileObject = new ImageEntity($file_path);
        $fileObject->show();
    }
}