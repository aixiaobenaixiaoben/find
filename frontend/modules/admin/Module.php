<?php

namespace app\modules\admin;

use common\models\admin\Admin;
use common\models\User;
use yii\web\HttpException;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\admin\controllers';

    public function init()
    {
        parent::init();
        $this->layout = 'site';
    }

    public function beforeAction($action)
    {
        if (!User::getCurrent() || !Admin::getCurrent()) {
            throw new HttpException(403, 'You are not an admin');
        }
        return parent::beforeAction($action);
    }
}
