<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\foundation;


/**
 * Class ImageService
 * @package app\foundation
 */
class ImageService
{

    /**
     * 递归创建指定目录
     * @param $dir
     */
    public static function createDir($dir)
    {
        mkdir($dir,0775,true);
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
}