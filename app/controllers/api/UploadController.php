<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\controllers\api;

use app\services\image\ImageService;
use Yii;
/**
 * api ERP上传商品主图 获取参数 图片路径 图片(Base64)
 * Class UploadController
 * @package app\controllers\api
 */
class UploadController extends BaseController
{
    const JSON = 'application/json';
    /**
     * 处理图片上传
     * 业务：ERP 推送商品图片
     * @return mixed json
     */
    public function actionHandle()
    {
        //请求内容格式校验
        $contentType = Yii::$app->request->getContentType();
        if($contentType != self::JSON) {
            Yii::error('content-type error','erp-image-upload-error');
            $result = [
                'version' => 1,
                'code' => 400,
                'description' => "[$contentType] invalid content type ,accept application/json"
            ];
            return $result;
        }
        $post = Yii::$app->request->post();
        if(empty($post)) {
            Yii::error('invalid params','erp-image-upload-error');
            $result = [
                'version' => 1,
                'code' => 400,
                'description' => 'invalid params'
            ];

            return $result;
        }
        $img_base_dir = Yii::$app->params['img_base_dir'];
        try {
            //删除旧目录下所有文件
            foreach ($post as $path => $value) {
                $sku_dir = $img_base_dir.dirname($path);
                if(is_dir($sku_dir)) {
                    $files = scandir($sku_dir);
                    foreach ($files as $file) {
                        $file_path = $sku_dir.'/'.$file;
                        if ($file != '.' && $file != '..' && !is_dir($file_path)) {
                            unlink($file_path);
                        }
                    }
                }
            }
            foreach ($post as $path => $img_base64) {
                $path     = $img_base_dir . $path;
                Yii::info($path,__METHOD__);
                $file_dir = dirname($path);
                if (!file_exists($file_dir)) {
                    ImageService::createDir($file_dir);
                }
                $img_tmp = base64_decode($img_base64);
                file_put_contents($path, $img_tmp);
            }
            $result = [
                'version' => 1,
                'code' => 200,
                'description' => 'Success'
            ];
            return $result;
        }catch (\Exception $e){
            Yii::error($e->getMessage(),'erp-image-upload-error');
            $result = [
                'version' => 1,
                'code' => 400,
                'description' => $e->getMessage()
            ];
            return $result;
        }
    }
}