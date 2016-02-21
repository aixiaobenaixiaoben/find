<?php

namespace frontend\modules\find\controllers;

use common\models\AjaxResponse;
use common\models\location\LocationNew;
use frontend\modules\find\forms\AddLocationForm;
use Yii;

class LocationController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionLocation($id)
    {
        $location = LocationNew::findOne($id);
        if ($location) {
            AjaxResponse::success(['location' => $location]);
        }
        AjaxResponse::fail();
    }


    public function actionAddLocation()
    {
        if (Yii::$app->request->isGet) {
            return $this->render('add-location');
        }
        $location = new AddLocationForm();
        if ($location->load(Yii::$app->request->post(), '') && $location->save()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $location->errors);
    }


}
