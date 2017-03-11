<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\controllers\api;

use app\exception\ImageNotFound;
use app\models\image\ImageEntity;
use app\services\image\ImageService;
use Yii;
/**
 * Class ImageController
 * @package app\controllers\api
 */
class ImageController extends BaseController
{

    /**
     * 批量获取商品主图
     * @return mixed  json
     */
    public function actionBatchFetchImages()
    {
        $params = Yii::$app->request->post();
        if(empty($params['skus'])) {
            $result = [
                'version' => 1,
                'code' => 400,
                'description' => 'error , invalid params',
                'result' => []
            ];

            return $result;
        }
        try {
            $skus = is_string($params['skus']) ? json_decode($params['skus']) : $params['skus'];
            $files = [];
            foreach ($skus as $sku) {
                $scandirs = ImageService::getScanDirsBySku($sku);
                $sku_files = [];
                foreach ($scandirs as $dir) {
                    $sku_files = ImageService::getFilesByDir($dir);
                    if(!empty($sku_files)) {
                        $sku_files = array_values($sku_files);
                        break;
                    }
                }
                $files[$sku] = $sku_files;
            }

            $result = [
                'version' => 1,
                'code' => 200,
                'description' => 'Success',
                'result' => $files
            ];
            return $result;
        } catch (\Exception $e) {

            $result = [
                'version' => 1,
                'code' => 400,
                'description' => 'server error',
                'result' => []
            ];

            return $result;
        }
    }


    /**
     * 图片展示 ERP调用接口开放权限
     * 相关业务：
     *  ERP推送图片根据根据sku查看图片 eg: images/I/4/IM1234/test.jpeg
     * @throws ImageNotFound
     */
    public function actionShow()
    {
        $file = $_GET['file'] ?? null;
        if (empty($file)) {
            throw new ImageNotFound('file path is empty');
        }
        $base_dir   = Yii::$app->params['product_origin_dir'];
        $file_path  = $base_dir . $file;
        $fileObject = new ImageEntity($file_path);
        $fileObject->show();
    }

}