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
use app\foundation\ThumbService;
use app\models\image\ImageEntity;
use app\services\image\ImageService;
use Yii;

class ContrastController extends BaseController
{
    public function actionIndex()
    {
        $filename = $_GET['filename'] ?? '';
        if(empty($filename)) {
            return $this->renderPartial('form');
        }

        $origin_file = Yii::$app->params['productInfo_origin_dir'].$filename;
        $file = new ImageEntity($origin_file);
        $size = $file->formatSize();
        $width = $file->getWidth();
        $height = $file->getHeight();
        return $this->renderPartial('contrast',[
                                                    'filename' => $filename,
                                                    'size' => $size,
                                                    'width' => $width,
                                                    'height' => $height,
                                                    'url' => Yii::$app->request->getHostInfo().'/images/uploads/'.$filename
                                                ]);
    }

    public function actionHandle()
    {
        $filename = Yii::$app->request->post('filename');
        $width = Yii::$app->request->post('width');
        $height = Yii::$app->request->post('height');
        $adapt = Yii::$app->request->post('adapt',1);
        $begin_quality = Yii::$app->request->post('begin_quality',50);
        $step = Yii::$app->request->post('step',5);

        if(empty($width)) {
            $width = $height;
        }
        if(empty($height)) {
            $height = $width;
        }

        //原图验证
        $origin_file = Yii::$app->params['productInfo_origin_dir'].$filename;

        if(empty($filename) || !file_exists($origin_file)) {
            $result = [
                'success' => 0,
                'message' => "$filename 图片不存在"
            ];
            return json_encode($result);
        }
        $file = new ImageEntity($origin_file);

        if(empty($width) && empty($height)) {
            $width = $file->getWidth();
            $height = $file->getHeight();
        }else if($adapt){
            $adaptSize = $file->adapt($width, $height);
            $width = $adaptSize['width'];
            $height = $adaptSize['height'];
        }

        //计算
        $result = ImageService::calculateQualityStep($begin_quality , $step);
        $contrast = [];
        //生成图片
        foreach ($result as $quality) {
            $size = ImageService::createSize($width, $height, $quality).'_'.$adapt;
            $target_path = Yii::$app->params['contrast_dir'].$size.'/'.$filename;
            if(!file_exists($target_path)) {
                ThumbService::resizeByGM($origin_file, $target_path, $width, $height, $quality);
            }
            $thumb = new ImageEntity($target_path);
            $thumb_info = $thumb->getInfo();
            $thumb_info['quality'] = $quality;
            $thumb_info['contrast_url'] = '/images/contrast/'.$size.'/'.$filename;
            $thumb_info['url'] =Yii::$app->request->getHostInfo().'/image/upload/'.$width.'-'.$height.'-'.$quality.'-'.$adapt.'/'.$filename;
            $contrast[] = $thumb_info;
        }
        $html = '<table>';
        foreach ($contrast as $thumb) {
            $html .= '<tr>';
            $html .= "<td><image src=\"". $thumb['contrast_url']. "\"></image></td></tr>";
            $html .= '<tr><td> <p>大小:'.$thumb['format_size'].'</p>';
            $html .= '<p>宽x高:'.$thumb['width'].'x'.$thumb['height'].'</p>';
            $html .= '<p>压缩质量比:'.$thumb['quality'].'</p>';
            $html .= '<p>url:'.$thumb['url'].'</p></td></tr>';
        }
        $html .= '</table>';
        $return = [
            'success' => 1,
            'message' => '图片对照生成成功',
            'html' => $html
        ];
        return json_encode($return);
    }



}