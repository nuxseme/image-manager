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
use app\services\image\ImageService;
use Yii;

/**
 * 图片上传处理器
 * 业务:处理商品详情图片上传
 * Class UploadController
 * @package app\controllers\img
 */
class UploadController extends BaseController
{

    /**
     * upload view
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial('upload');
    }


    /**
     * 处理上传图片
     * @return mixed json
     */
    public function actionHandle()
    {

        if (empty($_FILES['uploadfiles'])) {
            $result = [
                'files' => [
                    0 => [
                        'error' => '未收到上传文件'
                    ]
                ]
            ];
            return json_encode($result);
        }
        $images = $_FILES['uploadfiles'];
        //处理多数组
        try {
            $files      = [];
            $target_dir = Yii::$app->params['productInfo_origin_dir'];
            foreach ($images['tmp_name'] as $index => $image) {

                $file           = new \stdClass();
                $file->name     = $images['name'][$index];
                $file->tmp_name = $images['tmp_name'][$index];
                $file->type     = $images['type'][$index];
                $file->size     = $images['size'][$index];
                $file->error    = $images['error'][$index];

                if (!ImageService::verifyFileName($file->name)) {
                    $file->error = '文件名格式错误(请勿包含空格)';
                    $files[]     = $file;
                    break;
                }
                if (!ImageService::verifyFileType($file->type)) {
                    $file->error = '文件格式错误';
                    $files[]     = $file;
                    break;
                }
                //保存原图
                $target = $target_dir . $file->name;
                $save   = ImageService::save($file->tmp_name, $target);
                if ($save) {
                    $file->url          = '/images/uploads/' . $file->name;
                    $file->thumbnailUrl = $file->url;
                } else {
                    $file->error = '服务器保存图片失败';
                }

                $files[] = $file;
            }
            return json_encode(['files' => $files]);
        } catch (\Exception $e) {
            Yii::error($e->getMessage(),'image-upload');
            $result = [
                'files' => [
                    0 => [
                        'error' => 'server error'
                    ]
                ]
            ];
            return json_encode($result);
        }
    }
}