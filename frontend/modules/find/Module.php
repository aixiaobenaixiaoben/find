<?php

namespace frontend\modules\find;

use common\models\User;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\find\controllers';

    public function init()
    {
        parent::init();
    }

    public function beforeAction($action)
    {
        if (!User::getCurrent() ) {
            return Yii::$app->user->loginRequired();
        }
        return parent::beforeAction($action);
    }
}
