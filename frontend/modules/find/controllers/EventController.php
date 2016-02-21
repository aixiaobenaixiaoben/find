<?php

namespace frontend\modules\find\controllers;

use common\models\AjaxResponse;
use common\models\event\Event;
use frontend\modules\find\forms\CreateEventForm;
use Yii;

class EventController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionEvent($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && !$event->is_finished) {
            AjaxResponse::success(['event' => $event]);
        }
        AjaxResponse::fail();
    }

    public function actionGetEventLists($page = 1)
    {
        $rows_every_page = 5;
        $events = Event::find()
            ->where('is_finished=:is_finished', [':is_finished' => false])
            ->orderBy('created_at DESC')
            ->limit($rows_every_page)
            ->offset(($page - 1) * $rows_every_page + 1)
            ->all();
        return $this->render('event-list', ['events' => $events]);
    }

    public function actionCreateEvent()
    {
        if (Yii::$app->request->isGet) {
            return $this->render('create-event');
        }
        $event = new CreateEventForm();
        if ($event->load(Yii::$app->request->post(), '') && $event->save()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $event->errors);
    }

    public function actionRaiseEventUrgentLevel($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && !$event->is_finished && $event->urgent != Event::URGENT_EMERGENCY) {
            $event->urgent += 1;
            $event->save();
        }
        AjaxResponse::success();
    }

    public function actionModerateEventUrgentLevel($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && !$event->is_finished && $event->urgent != Event::URGENT_MILD) {
            $event->urgent -= 1;
            $event->save();
        }
        AjaxResponse::success();
    }

    public function actionFinishEvent($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && !$event->is_finished) {
            $event->is_finished = true;
            $event->save();
        }
        AjaxResponse::success();
    }


}
