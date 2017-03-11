<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\foundation;

use PHPThumb\GD;
use Yii;

/**
 * Class ThumbService
 * @package app\services\image
 */
class ThumbService
{
    /**
     * 采用gd 图片等比缩略
     * 未设置图片修复策略 遇到图片文件结尾字符错误 gd库获取图片会报错
     * @param $file
     * @param $target_path
     * @param $width
     * @param $height
     * @param int $quality
     */
    public static function resizeByGD($file, $target_path,$width, $height, $quality = 100 )
	 {
		$target_dir = dirname($target_path);
		if(!file_exists($target_dir)) {
		    ImageService::createDir($target_dir);
		}
		$thumb = new GD($file);
		//设置jpg格式的保存质量
		$thumb->setOptions(['jpegQuality' => $quality]);
         //默认等比缩放
		$thumb->resize($width, $height);
		$thumb->save($target_path);
	 }

    /**
     * @param $file
     * @param $target_path
     * @param $width
     * @param $height
     * @param int $quality
     * @param bool $adapt
     * @throws \Exception
     */
    public static function resizeByGM($file,$target_path,$width,$height,$quality = 100 , $adapt = false)
    {
        $target_dir = dirname($target_path);
        if(!is_dir($target_dir)) {
            ImageService::createDir($target_dir);
        }
        if($adapt) {
            //等比缩略
            if($quality == 100 )
            {
                $command = "gm convert  -thumbnail ".$width."x".$height." $file $target_path";
            }else {
                $command = "gm convert  -thumbnail ".$width."x".$height." -quality $quality $file $target_path";
            }

        } else {
            //强制按给定的尺寸缩略
            if($quality == 100 )
            {
                $command = "gm convert  -thumbnail ".$width."x".$height."! $file $target_path";
            }else {
                $command = "gm convert  -thumbnail ".$width."x".$height."! -quality $quality $file $target_path";
            }

        }
        exec($command, $output, $return);
        if($return) {
            throw new \Exception($command. ' exit with '.$return);
        }
    }

}