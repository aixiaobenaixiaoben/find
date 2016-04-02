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
     **定义用户访问各个方法时只能选择的请求方法的种类(post还是get).
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

    /**
     **显示一个事件详情,包括涉事儿童信息和当前节点简要信息.
     * @param $id
     * @return string
     * @throws HttpException
     */
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
            ->with(['user', 'profiles'])
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

    /**
     **显示事件列表,根据参数确定显示进行中事件还是已结束事件,每个事件同时显示涉事儿童姓名.
     * @param int $is_finish
     * @param int $page
     * @return string
     */
    public function actionEventLists($is_finish = 0, $page = 1)
    {
        $rows_every_page = 20;
        $events = Event::find()
            ->with('profiles')
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

    /**
     **显示创建一个事件需要填的表单(get);创建一个新的事件(post),同时创建一个新节点.
     * @return string
     */
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

    /**
     **显示添加新节点需要的表单(get);为事件添加一个新的节点(post),如果该节点可信,则同时创建一个当前节点.
     * @param int $id
     * @return string
     */
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

    /**
     **(如果当前该事件的紧急程度不是最高)提高一个进行中事件的紧急程度(紧急程度越高短信通知时覆盖范围更广).
     * @param $id
     * @return \yii\web\Response
     */
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

    /**
     **(如果当前该事件的紧急程度不是最低)降低一个进行中事件的紧急程度(紧急程度越低短信通知时覆盖范围更小).
     * @param $id
     * @return \yii\web\Response
     */
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

    /**
     **将一个进行中事件标记为结束状态
     * @param $id
     * @return \yii\web\Response
     */
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

    /**
     **将一个已结束事件恢复为进行中状态
     * @param $id
     * @return \yii\web\Response
     */
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

    /**
     **在地图上显示一个事件中所有节点,并按照节点发生时间先后顺序绘出节点间轨迹图,打开地图时地图中心位置节点为起始节点,轨迹尾端为发生时间最晚节点.
     * @param $id
     * @return \yii\web\Response
     */
    public function actionViewRouteOnMap($id)
    {
        $event = Event::findOne($id);
        if ($event) {
            $name = substr(hash('md5', $id), 0, 10);
            return $this->redirect("http://forfreedomandlove.com/maps/$name.html");
        }
        return $this->redirect(['/find/event/event/' . $id]);
    }

    /**
     **显示针对该事件将要发送短信所需信息的表单,表单中已有信息,在确认发送短信前可以对某些信息进行调整,其中针对涉事儿童个人信息的修改,可以同时更新数据库中该涉事儿童的个人信息.
     * @param $id
     * @return string
     * @throws HttpException
     */
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
                , 'profiles'])
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

    /**
     **针对某一事件,向该事件中最晚时间节点周围一定半径区域内的手机讯号发送短信,半径大小由事件紧急程度决定,紧急程度越高半径越大,其中关于某个事件只能向某个手机号码发送一次短信.
     */
    public function actionSendMessage()
    {
        $sendMessageForm = new SendMessageForm();
        if ($sendMessageForm->load(Yii::$app->request->post(), '') && $sendMessageForm->save()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $sendMessageForm->errors);
    }


}
