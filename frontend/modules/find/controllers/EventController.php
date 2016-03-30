<?php

namespace frontend\modules\find\controllers;

use common\models\AjaxResponse;
use common\models\event\Event;
use common\models\location\LocationNew;
use frontend\modules\find\forms\AddLocationForm;
use frontend\modules\find\forms\CreateEventForm;
use frontend\modules\find\forms\SendMessageForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;

class EventController extends \yii\web\Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'event' => ['get'],
                    'event-lists' => ['get'],
                    'create-event' => ['get', 'post'],
                    'add-location' => ['get', 'post'],
                    'raise-urgent-level' => ['get'],
                    'moderate-urgent-level' => ['get'],
                    'finish-event' => ['get'],
                    'recover-event' => ['get'],
                    'view-route-on-map' => ['get'],
                    'pre-send-message' => ['get'],
                    'send-message' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionEvent($id)
    {
        /** @var LocationNew $location_new */
        $location_new = LocationNew::find()
            ->where('event_id=:event_id and is_reliable=1', [':event_id' => $id])
            ->with('provider')
            ->orderBy('occur_at DESC')
            ->one();

        /** @var Event $event */
        $event = Event::find()
            ->where('id=:id', [':id' => $id])
            ->with(['user', 'profiles' => function ($query) {
                $query->limit(1);
            }])
            ->one();

        if (!$location_new || !$event) {
            throw new HttpException(404, 'The resource you request does not exist');
        }
        return $this->render('event', [
            'event' => $event,
            'profile' => $event->profiles[0],
            'location_new' => $location_new,
            'provider' => $location_new->provider,
        ]);
    }

    public function actionEventLists($is_finish = 0, $page = 1)
    {
        $rows_every_page = 20;
        $events = Event::find()
            ->with(['profiles' => function ($query) {
                $query->limit(1);
            }])
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
            $csrf = Yii::$app->request->csrfToken;
            return $this->render('create-event', ['csrf' => $csrf]);
        }
        $event = new CreateEventForm();
        if ($event->load(Yii::$app->request->post(), '') && $event->save()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $event->errors);
    }

    public function actionAddLocation($id = 0)
    {
        if (Yii::$app->request->isGet) {
            $csrf = Yii::$app->request->csrfToken;
            return $this->render('add_location', [
                'event_id' => $id,
                'csrf' => $csrf
            ]);
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
        return $this->redirect(['/find/event/event/' . $id]);
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
        return $this->redirect(['/find/event/event/' . $id]);
    }

    public function actionFinishEvent($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && !$event->is_finished) {
            $event->is_finished = true;
            $event->save();
        }
        return $this->redirect(['/find/event/event/' . $id]);
    }

    public function actionRecoverEvent($id)
    {
        /** @var Event $event */
        $event = Event::findOne($id);
        if ($event && $event->is_finished) {
            $event->is_finished = false;
            $event->save();
        }
        return $this->redirect(['/find/event/event/' . $id]);
    }

    public function actionViewRouteOnMap($id)
    {
        $event = Event::findOne($id);
        if ($event) {
            $name = substr(hash('md5', $id), 0, 10);
            return $this->redirect("http://forfreedomandlove.com/maps/$name.html");
        }
        return $this->redirect(['/find/event/event/' . $id]);
    }

    public function actionPreSendMessage($id)
    {
        /** @var Event $event */
        $event = Event::find()
            ->where('id=:id', [':id' => $id])
            ->with([
                'locationCurrents' => function ($query) {
                    $query->orderBy('occur_at DESC')
                        ->limit(1);
                }
                , 'profiles' => function ($query) {
                    $query->limit(1);
                }])
            ->one();
        if (!$event) {
            throw new HttpException(404, 'The resource you requested does not exist');
        }
        if ($event->is_finished) {
            throw new HttpException(405, 'The resource you requested has expired');
        }
        $csrf = Yii::$app->request->csrfToken;
        return $this->render('send_message', [
            'event' => $event,
            'current' => $event->locationCurrents[0],
            'profile' => $event->profiles[0],
            'csrf' => $csrf
        ]);
    }

    public function actionSendMessage()
    {
        $sendMessageForm = new SendMessageForm();
        if ($sendMessageForm->load(Yii::$app->request->post(), '') && $sendMessageForm->save()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $sendMessageForm->errors);
    }


}
