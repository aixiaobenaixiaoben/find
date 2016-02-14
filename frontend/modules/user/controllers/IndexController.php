<?php

namespace frontend\modules\user\controllers;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
