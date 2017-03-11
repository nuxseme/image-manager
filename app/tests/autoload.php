<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

class Loader
{
    public static function autoload($className)
    {
        $file = __DIR__.'/../../'.$className.'.php';
        if(file_exists($file)) {
            require_once __DIR__.'/../../'.$className.'.php';
        }

    }
}
spl_autoload_register('Loader::autoload');


