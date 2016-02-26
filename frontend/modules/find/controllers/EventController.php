<?php

namespace frontend\modules\find\controllers;

use common\models\AjaxResponse;
use common\models\event\Event;
use common\models\location\LocationCurrent;
use common\models\location\LocationNew;
use frontend\modules\find\forms\AddLocationForm;
use frontend\modules\find\forms\CreateEventForm;
use Yii;
use yii\web\HttpException;

class EventController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionEvent($id)
    {
        /** @var LocationNew $location_new */
        $location_new = LocationNew::find()
            ->where('event_id=:event_id and is_reliable=1', [':event_id' => $id])
            ->with(['event.user', 'provider'])
            ->orderBy('occur_at DESC')
            ->one();

        if (!$location_new) {
            throw new HttpException(404, 'The resource you request does not exist');
        }
        return $this->render('event', [
            'event' => $location_new->event,
            'location_new' => $location_new,
            'provider' => $location_new->provider,
        ]);
    }

    public function actionEventLists($is_finish = 0, $page = 1)
    {
        $rows_every_page = 5;
        $events = Event::find()
            ->where('is_finished=:is_finished', [':is_finished' => $is_finish])
            ->orderBy('created_at DESC')
            ->limit($rows_every_page)
            ->offset(($page - 1) * $rows_every_page)
            ->all();
        return $this->render('event-list', [
            'events' => $events,
            'is_finish' => $is_finish
        ]);
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

    public function actionAddLocation($id=0)
    {
        if (Yii::$app->request->isGet) {
            return $this->render('add_location', ['event_id' => $id]);
        }
        $location = new AddLocationForm();
        if ($location->load(Yii::$app->request->post(), '') && $location->save()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $location->errors);
    }

    public function actionRaiseUrgentLevel($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && !$event->is_finished && $event->urgent != Event::URGENT_EMERGENCY) {
            if ($event->urgent == Event::URGENT_MILD) {
                $event->urgent = Event::URGENT_URGENT;
            } elseif ($event->urgent == Event::URGENT_URGENT) {
                $event->urgent = Event::URGENT_EMERGENCY;
            }
            $event->save();
        }
        $this->redirect(['/find/event/event/' . $id]);
    }

    public function actionModerateUrgentLevel($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && !$event->is_finished && $event->urgent != Event::URGENT_MILD) {
            if ($event->urgent == Event::URGENT_EMERGENCY) {
                $event->urgent = Event::URGENT_URGENT;
            } elseif ($event->urgent == Event::URGENT_URGENT) {
                $event->urgent = Event::URGENT_MILD;
            }
            $event->save();
        }
        $this->redirect(['/find/event/event/' . $id]);
    }

    public function actionFinishEvent($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && !$event->is_finished) {
            $event->is_finished = true;
            $event->save();
        }
        $this->redirect(['/find/event/event/' . $id]);
    }

    public function actionRecoverEvent($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && $event->is_finished) {
            $event->is_finished = false;
            $event->save();
        }
        $this->redirect(['/find/event/event/' . $id]);
    }


}
