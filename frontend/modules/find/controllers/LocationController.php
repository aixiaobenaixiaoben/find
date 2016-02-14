<?php

namespace frontend\modules\find\controllers;

class LocationController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionLocation()
    {
        return $this->render('location');
    }

    public function actionPreAddLocation()
    {

    }

    public function actionAddLocation()
    {

    }

    public function actionUnReliableLocation()
    {

    }

}
