<?php

namespace frontend\modules\user;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\user\controllers';

    /**
     **初始化该模块,同时默认该模块页面采用自定义的main.php所定义的模板
     */
    public function init()
    {
        parent::init();
    }
}
