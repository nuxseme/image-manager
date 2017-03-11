<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\services;

use Yii;
class TokenService extends BaseService
{
    const WEBPOS = 'token_webpos';

    public static function verify($type,$token)
    {
        switch (trim($type)) {
            case self::WEBPOS;
                break;
            default:
                throw new \InvalidArgumentException('token type 无效');
        }
        $token = trim($token);
        if(empty($token)) {
            throw new \InvalidArgumentException('token不能为空');
        }
        if($token != Yii::$app->params[self::WEBPOS]) {
            throw new \InvalidArgumentException('无效token');
        }
        return true;
    }
}