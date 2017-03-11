<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\services\image;

use app\services\BaseService;
use Yii;

/**
 * Class ImageService
 * @package app\services\image
 */
class ImageService extends BaseService
{

    /**
     * 匹配图片类型
     * @param $type
     * @return int
     */
    public static function verifyFileType($type)
    {
        return (preg_match('/^image\/(gif|jpe?g|png)/', $type) || preg_match('/(gif|jpe?g|png)/', $type));
    }

    /**
     * @param $file_name
     * @return bool
     */
    public static function verifyFileName($file_name)
    {
       return !strstr($file_name,' ');
    }


    /**
     * 保存文件
     * @param $filename
     * @param $target
     * @return bool
     */
    public static function save($filename, $target)
    {
        if(!is_dir(dirname($target))) {
            self::createDir(dirname($target));
        }
        return move_uploaded_file($filename,$target);
    }


    /**
     * 递归创建目录
     * @param $dir
     */
    public static function createDir($dir)
    {
        mkdir($dir,0775,true);
    }


    /**
     * 删除文件
     * @param $target
     */
    public static function delete($target)
    {
        if(file_exists($target)) {
           unlink($target);
        }
    }

    public static function isImageExist(\stdClass $file)
    {
        $origin_dir = Yii::$app->params['origin_dir'];
        $target_path = $origin_dir.$file->name;
        return is_file($target_path);
    }

    /**
     * @param $sku
     * @return array
     */
    public static function getScanDirsBySku($sku)
    {
        $first = substr( $sku, 0, 1 );
        $end = substr( $sku, -1);
        //图片查询目录 形如 I/IM1234  或者 I/4/IM1234
        $dirs = [
            $first.'/'.$sku,
            $first.'/'.$end.'/'.$sku
        ];
        return $dirs;
    }

    public static function getFilesByDir($scandir)
    {
        $base_dir = Yii::$app->params['product_origin_dir'];
        $dir = $base_dir.$scandir;
        if(!is_dir($dir)) {
            return [];
        }
        $files = scandir($dir);
        $host = Yii::$app->request->getHostInfo();
        foreach ($files as $index => &$file) {
            if($file == '.' || $file == '..' || is_dir($file)) {
                unset($files[$index]);
            }else{
                $file = $host.'/images/'.$scandir.'/'.$file;
            }
        }
        return $files;
    }

    /**
     * 根据sku  图片名  图片类型  返回图片路径
     * @param $sku
     * @param $image_name
     * @return bool|string
     */
    public static function getFileByNameAndSku($sku, $image_name)
    {
        $scandir = self::getScanDirsBySku($sku);
        $base_dir = Yii::$app->params['product_origin_dir'];
        foreach ($scandir as $dir) {
            $target_path = $base_dir.$dir.'/'.$image_name;
            if(file_exists($target_path)) {
                return $target_path;
            }
        }
        return false;
    }

    /**
     * 根据sku size image_name 返回缩略图路径
     * @param $sku
     * @param $size
     * @param $file_name
     * @return bool|string
     */
    public static function getThumb($sku,$size,$file_name)
    {
        $scandirs = self::getScanDirsBySku($sku);
        $thumb_base_dir =  Yii::$app->params['product_thumb_dir'];
        foreach ($scandirs as $dir) {
            $target_path = $thumb_base_dir.$dir.'/'.$size.'/'.$file_name;
            if(file_exists($target_path)) {
                return $target_path;
            }
        }
        return false;
    }

    /**
     * 根据 长宽质量 返回size
     * @param $w
     * @param $h
     * @param $p
     * @return string
     */
    public static function createSize($w, $h, $p)
    {
        $suffix =  $p == 100 ? '1.0' : $p/100;
        return $w.'_'.$h.'_'.$suffix;
    }

    /**
     * 创建缩略图路径
     * 缩略图保存到
     * @param $sku
     * @param $size
     * @param $file_name
     * @return string
     */
    public static function createThumbPath($sku, $size, $file_name)
    {
        $thumb_base_dir = Yii::$app->params['product_thumb_dir'];
        $first = substr( $sku, 0, 1 );
        $end = substr( $sku, -1);
        $target_path = $thumb_base_dir.$first.'/'.$end.'/'.$sku.'/'.$size.'/'.$file_name;
        return $target_path;
    }

    public static function calculateQualityStep($begin_quality, $step)
    {
        $times = floor( (100 - $begin_quality) / $step);
        $result = [$begin_quality];
        for($i = 1;$i <= $times; $i++) {
            $quality = $begin_quality + $i * $step;
            if($quality != 100) {
                $result[] = $quality;
            }
        }
        rsort($result);
        return $result;
    }

}