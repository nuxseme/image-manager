<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\controllers\image;

use app\controllers\BaseController;
use app\exception\ImageNotFound;
use app\foundation\ThumbService;
use app\models\image\ImageEntity;
use app\services\image\ImageService;
use Yii;
use yii\web\Response;

/**
 * Class ImageController
 * @package app\controllers
 */
class ShowController extends BaseController
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\HttpCache',
                'only' => ['index'],
                'lastModified' => function ($action, $params) {
                    return $this->getLastModifyTime();
                },
            ],
        ];
    }

    /**
     * 根据sku 查看对应商品图片
     * @return string
     * @throws ImageNotFound
     */
    public function actionShowImages()
    {
        if(empty($_GET['sku'])) {
           throw new ImageNotFound();
        }
        $sku      = $_GET['sku'];
        $scandirs = ImageService::getScanDirsBySku($sku);
        $files    = [];
        foreach ($scandirs as $dir) {
            $files = ImageService::getFilesByDir($dir);
            if (!empty($files)) {
                break;
            }
        }
        return $this->renderPartial('show_images', ['files' => $files]);
    }


    /**
     * 图片展示
     * 相关业务：
     *  本地上传后 图片显示  eg: images/uploads/test.jpeg
     * @throws ImageNotFound
     */
    public function actionIndex()
    {
        $file = $_GET['file'] ?? null;
        if (empty($file)) {
            throw new ImageNotFound('file path is empty');
        }
        $base_dir   = Yii::$app->params['productInfo_origin_dir'];
        $file_path  = $base_dir . $file;
        $fileObject = new ImageEntity($file_path);
        //$fileObject->show();
        $file_content = $fileObject->getContent();
        $mime = $fileObject->getMime();
        return $this->send($file_content,$mime);
    }


    /**
     * 获取上传图片缩略图
     * @throws ImageNotFound
     */
    public function actionUpload()
    {
        $w = $_GET['w'];
        $h = $_GET['h'];
        $p = $_GET['p'];
        $adapt = $_GET['adapt'];
        $filename = $_GET['image_name'];

        if(empty($w) || empty($h) || empty($p) || !isset($adapt) || empty($filename)) {
            throw new ImageNotFound();
        }

        $target_path = Yii::$app->params['upload_thumb_dir'].$w.'_'.$h.'_'.$p.'_'.$adapt.'/'.$filename;
        if(file_exists($target_path)) {
            $file = new ImageEntity($target_path);
            $file->show();
        }
        $origin_file = Yii::$app->params['productInfo_origin_dir'].$filename;
        ThumbService::resizeByGM($origin_file, $target_path, $w, $h, $p, $adapt);
        $file = new ImageEntity($target_path);
        $file->show();
    }

    //查找原图最后修改时间
    public function getLastModifyTime()
    {
        $file = $_GET['file'] ?? null;
        if (empty($file)) {
            throw new ImageNotFound('file path is empty');
        }
        $base_dir   = Yii::$app->params['productInfo_origin_dir'];
        $file_path  = $base_dir . $file;
        $fileObject = new ImageEntity($file_path);
        return $fileObject->getLastModifyTime();
    }

    public function send($content,$mime)
    {
        $response = \Yii::$app->getResponse();
        $response->headers->set('Content-Type', $mime);
        $response->format = Response::FORMAT_RAW;
        $response->content = $content;
        return $response->send();
    }
}