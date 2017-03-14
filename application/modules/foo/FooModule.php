<?php

namespace application\modules\foo;

use yii\base\Module;

/**
 * foo module definition class
 */
class FooModule extends Module
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . '/config/config.php'));
    }
}
