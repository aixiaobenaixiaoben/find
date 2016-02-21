<?php

namespace frontend\modules\find\controllers;

use common\models\AjaxResponse;
use frontend\modules\find\forms\AddLocationForm;
use Yii;

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
        $location = new AddLocationForm();
        if ($location->load(Yii::$app->request->post(), '') && $location->save()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $location->errors);
    }

    public function actionUnReliableLocation()
    {

    }

}
