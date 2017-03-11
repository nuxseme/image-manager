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
 * 商品详情图片替换
 * Class ReplaceController
 * @package app\controllers\image
 */
class ReplaceController extends BaseController
{

    /**
     * replace  view
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial('replace');
    }

    /**
     * replace  handle
     * @return  mixed json
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

        try {
            //处理多数组
            $files      = [];
            $target_dir = Yii::$app->params['productInfo_origin_dir'];
            foreach ($images['tmp_name'] as $index => $image) {

                $file           = new \stdClass();
                $file->name     = $images['name'][$index];
                $file->tmp_name = $images['tmp_name'][$index];
                $file->type     = $images['type'][$index];
                $file->size     = $images['size'][$index];
                $file->error    = $images['error'][$index];
                if(!ImageService::verifyFileName($file->name)) {
                    $file->error = '文件名格式错误(请勿包含空格)';
                    $files[] = $file;
                    break;
                }
                if(!ImageService::verifyFileType($file->type)) {
                    $file->error = '文件格式错误';
                    $files[] = $file;
                    break;
                }
                //检测原图是否存在
                $target = $target_dir . $file->name;
                if(!file_exists($target)) {
                    $file->error = '图片不存在，替换失败';
                }else{
                    //删除原图
                    ImageService::delete($target);
                    //保存图片
                    $save   = ImageService::save($file->tmp_name, $target);
                    if ($save) {
                        $file->url          = '/images/uploads/' . $file->name;
                        $file->thumbnailUrl = $file->url;
                    } else {
                        $file->error = '服务器保存图片失败';
                    }
                }
                $files[] = $file;
            }
            return json_encode(['files' => $files]);
        } catch (\Exception $e) {
            Yii::error($e->getMessage(),'replace-handle');
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