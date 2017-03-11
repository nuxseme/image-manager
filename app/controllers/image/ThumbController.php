<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\controllers\image;

use app\exception\ImageNotFound;
use app\foundation\ThumbService;
use app\helpers\Validate;
use app\models\image\ImageEntity;
use app\services\image\ImageService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;

/**
 * 获取商品缩略图
 * 业务:ERP推送商品主图存储到eg(I\ 或者 I\0 目录下)，根据指定的size quality 生成 返回缩略图
 * Class ThumbController
 * @package app\controllers\img
 */
class ThumbController extends Controller
{

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\HttpCache',
                'only' => ['fetch'],
                'lastModified' => function ($action, $params) {
                    return $this->getLastModifyTime();
                },
            ],
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionFetch()
    {
        $image_name = isset($_GET['image_name']) ? $_GET['image_name']:null;
        $sku = isset($_GET['sku']) ? $_GET['sku']:null;
        $h = isset($_GET['h'])?$_GET['h']:null;
        $w = isset($_GET['w'])?$_GET['w']:null;
        $p = isset($_GET['p'])?$_GET['p']:75;
        $validate = [
            'image_name' => $image_name,
            'sku' => $sku,
        ];
        //参数校验
        Validate::notEmpty($validate);
        //没有获取到长宽 根据sku查找原图获取图片属性
        if (empty($w) || empty($h)) {
            $origin_file = ImageService::getFileByNameAndSku($sku, $image_name);

            if(!$origin_file){
                throw new ImageNotFound();
            }

            $image = new ImageEntity($origin_file);
            $h = $image->getHeight();
            $w = $image->getWidth();
        }

        //获取指定缩略图
        $size = ImageService::createSize($w,$h,$p);
        $thumb = ImageService::getThumb($sku, $size, $image_name);
        if ($thumb) {
            $thumb = new ImageEntity($thumb);
            //$thumb->show();
            $file_content = $thumb->getContent();
            $mime = $thumb->getMime();
            return $this->send($file_content,$mime);
        }

        //未找到缩略图 根据原图重新生成
        $origin_file = ImageService::getFileByNameAndSku($sku, $image_name);
        if(!$origin_file) {
            throw new ImageNotFound();
        }
        $target_path = ImageService::createThumbPath($sku, $size, $image_name);
        ThumbService::resizeByGM($origin_file, $target_path, $w, $h, $p, true);
        $thumb = new ImageEntity($target_path);
        //$thumb->show();
        $file_content = $thumb->getContent();
        $mime = $thumb->getMime();
        return $this->send($file_content,$mime);
    }

    public function send($content,$mime)
    {
        $response = \Yii::$app->getResponse();
        $response->headers->set('Content-Type', $mime);
        $response->format = Response::FORMAT_RAW;
        $response->content = $content;
        return $response->send();
    }

    //查找原图最后修改时间
    public function getLastModifyTime()
    {
        $image_name = isset($_GET['image_name']) ? $_GET['image_name']:null;
        $sku = isset($_GET['sku']) ? $_GET['sku']:null;
        $origin_file = ImageService::getFileByNameAndSku($sku, $image_name);
        $last_modify_time = is_file($origin_file) ? filemtime($origin_file) : null;
        return $last_modify_time;
    }

}