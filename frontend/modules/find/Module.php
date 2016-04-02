<?php

namespace frontend\modules\find;

use common\models\User;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\find\controllers';

    /**
     **初始化该模块,同时默认该模块页面采用自定义的main.php所定义的模板
     */
    public function init()
    {
        parent::init();
    }

    /**
     **在请求交由action处理之前,判断用户属性,如果当前用户没有登录,则将页面跳转到登录页面,即该模块的所有操作都需要在用户登录状态下进行.
     * @param \yii\base\Action $action
     * @return bool|\yii\web\Response
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        if (!User::getCurrent() ) {
            return Yii::$app->user->loginRequired();
        }
        return parent::beforeAction($action);
    }
}
