<?php

namespace frontend\modules\admin\controllers;

use yii\web\Controller;

class IndexController extends Controller
{
    /**
     * 显示管理模块的首页,首页中呈现系统中建立模型的索引
     * @return string Page to display directory of Admin Module
     */
    public function actionIndex()
    {
        $models = [
            'user',
            'admin',
            'event',
            'profile',
            'location-provider',
            'location-new',
            'location-current',
            'message-record',
        ];
        return $this->render('index', ['models' => $models]);
    }
}
