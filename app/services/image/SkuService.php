<?php
/**
 * @link http://www.tomtop.com/
 * @copyright Copyright (c) 2016 TOMTOP
 * @license http://www.tomtop.com/license/
 *
 */

namespace app\services\image;


class SkuService
{
    public static function createPath($sku)
    {
        if(empty($sku)) {
            return false;
        }
        $sku = trim($sku);
        $first = substr( $sku, 0, 1 );
        $end = substr( $sku, -1);
        return $first.'/'.$end.'/';
    }
}