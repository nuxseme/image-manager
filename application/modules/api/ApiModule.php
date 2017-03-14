<?php

namespace application\modules\api;

use yii\base\Module;

/**
 * foo module definition class
 */
class ApiModule extends Module
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config/config.php'));
    }
}
