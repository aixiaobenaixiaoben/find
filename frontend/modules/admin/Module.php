<?php

namespace frontend\modules\admin;

use common\models\admin\Admin;
use common\models\User;
use yii\web\HttpException;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\admin\controllers';

    /**
     **初始化该模块,强调该模块的模板使用site.php所定义的模板
     */
    public function init()
    {
        parent::init();
        $this->layout = '@frontend/views/layouts/site.php';
    }

    /**
     **在请求交由action处理之前,判断用户属性,如果当前用户没有登录,或者登录用户没有管理员权限,那么抛出403异常,即只有管理员才能进入该管理模块.
     * @param \yii\base\Action $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {
        if (!User::getCurrent() || !Admin::getCurrent()) {
            throw new HttpException(403, 'You are not an admin');
        }
        return parent::beforeAction($action);
    }
}
