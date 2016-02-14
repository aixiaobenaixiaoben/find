<?php

namespace frontend\modules\find\controllers;

use common\models\AjaxResponse;
use frontend\modules\find\forms\CreateEventForm;
use Yii;

class EventController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionEvent()
    {
        return $this->render('event');
    }

    public function actionGetEventLists()
    {

    }

    public function actionPreCreateEvent()
    {

    }

    public function actionCreateEvent()
    {
        $event = new CreateEventForm();
        if ($event->load(Yii::$app->request->post(), '') && $event->save()) {
            AjaxResponse::success([
                'event' => $event->getEvent(),
                'provider' => $event->getProvider(),
                'location_new' => $event->getLocationNew(),
                'location_current' => $event->getLocationCurrent()
            ]);
        }
        AjaxResponse::fail(null, $event->errors);
    }

    public function actionChangeEventUrgentLevel()
    {

    }


}
