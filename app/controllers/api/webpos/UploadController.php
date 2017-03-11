<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */
namespace app\controllers\api\webpos;

use app\controllers\api\BaseController;
use app\foundation\ImageService;
use app\services\image\SkuService;
use app\services\TokenService;
use Yii;

/**
 * Class UploadController
 * @package app\controllers\api\webpos
 */
class UploadController extends BaseController
{
    const JSON = 'application/json';
    /**
     * webpos 项目图片上传接口
     * 业务描述：图片服务器接收webpos
     */
    public function actionHandle()
    {
        //请求内容格式校验
        $contentType = Yii::$app->request->getContentType();
        if($contentType != self::JSON) {

            return fail("invalid content type, only accept application/json");
        }
        $post = Yii::$app->request->post();
        $token = $post['token'] ?? '';
        try {
            TokenService::verify(TokenService::WEBPOS,$token);
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
        $files = $post['files'] ?? [];
        //参数解析
        if(empty($files)) {
            Yii::error('post is empty','webpos-image-upload-error');
            return fail('Not yet received the data');
        }
        $webpos_img_base_dir = Yii::$app->params['webpos_root'];
        $result = [];
        try {
            //接收处理
            foreach ($files as $key => $item) {
                $sku = $item['sku'];
                $file_name = $item['file_name'];
                $uuid = $item['partner'];
                $sku_path = SkuService::createPath($sku);
                $file_path = $webpos_img_base_dir.$sku_path.$sku.'/'.$uuid.'/'.$file_name;

                //判断文件是否存在 存在先删除 然后重新保存
                if(is_file($file_path)) {
                    unlink($file_path);
                }
                $file_dir = dirname($file_path);
                if (!is_dir($file_dir)) {
                    ImageService::createDir($file_dir);
                }
                $img_base64 = $item['data'];
                $img_tmp = base64_decode($img_base64);
                file_put_contents($file_path,$img_tmp);

                $result[$key]['sku'] = $sku;
                $result[$key]['file_name'] = $file_name;
                $result[$key]['url'] = '/webpos/'.$uuid.'/'.$sku.'/'.$file_name;

            }

            return success('success',0,$result);
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
}