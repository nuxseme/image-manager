<?php
/**
 * @link http://www.tomtop.com/
 * @copyright Copyright (c) 2016 TOMTOP
 * @license http://www.tomtop.com/license/
 *
 */

/**
 * @param string $message
 * @param int $code
 * @param array $beans
 * @return string
 */
function success($message = 'success',$code = 0,$beans = [])
{
    $object = new \stdClass();
    $object->result = true;
    $object->message = trim($message);
    $object->code = intval($code);
    !empty($beans) && $object->beans = $beans;
    return $object;
}

/**
 * @param string $message
 * @param int $code
 * @param array $beans
 * @return string
 */
function fail($message = 'fail',$code = 1,$beans = [])
{
    $object = new \stdClass();
    $object->result = false;
    $object->message = trim($message);
    $object->code = intval($code);
    !empty($beans) && $object->beans = $beans;
    return $object;
}
