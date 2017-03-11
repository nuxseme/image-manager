<?php
/**
 * @link http://www.tomtop.com/
 * @copyright Copyright (c) 2016 TOMTOP
 * @license http://www.tomtop.com/license/
 *
 */

namespace app\controllers\api\webpos;


use app\exception\ImageNotFound;
use app\foundation\ThumbService;
use app\models\image\ImageEntity;
use app\services\image\SkuService;
use yii\web\Controller;
use Yii;

class ThumbController extends Controller
{
    /**
     *缩略图操作
     */
    public function actionHandle()
    {
        $uuid = $_GET['uuid'];
        $sku = $_GET['sku'];
        $w = $_GET['w'];
        $h = $_GET['h'];
        $p = $_GET['p'];
        $file_name = $_GET['image_name'];
        $webpos_thumb_root = Yii::$app->params['webpos_thumb_root'];
        $size = $w.'-'.$h.'-'.$p;
        $thumb_path = $webpos_thumb_root.$sku.'/'.$size.'/'.$uuid.'/'.$file_name;
        //查找缩略图 缩略图存在返回缩略图

        if(is_file($thumb_path)) {
            $thumb = new ImageEntity($thumb_path);
            $thumb->show();
        }

        //缩略图不存在 查找原图生成缩略图
        $webpos_root = Yii::$app->params['webpos_root'];
        $sku_path = SkuService::createPath($sku);
        $origin_file_path = $webpos_root.$sku_path.$sku.'/'.$uuid.'/'.$file_name;
        if(!is_file($origin_file_path)) {
            throw new ImageNotFound();
        }
        ThumbService::resizeByGM($origin_file_path, $thumb_path, $w, $h, $p, true);
        $thumb = new ImageEntity($thumb_path);
        $thumb->show();
    }
}