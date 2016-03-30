<?php

namespace frontend\modules\admin\controllers;

use yii\web\Controller;

class IndexController extends Controller
{
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
