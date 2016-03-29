<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/13
 * Time: 13:51
 */
namespace frontend\modules\find\forms;

use common\exceptions\TelecomsLocateFailException;
use common\exceptions\MapWithTitleException;
use common\models\event\Event;
use common\models\location\LocationNew;
use common\models\location\LocationProvider;
use Yii;
use yii\base\Model;
use yii\db\Exception;

class AddLocationForm extends Model
{
    public $event_id;
    public $city;
    public $title_from_provider;
    public $occur_at;
    public $provided_at;
    public $identity_kind;

    public $identity_info;

    private $_event;
    private $_provider;
    private $_location_new;

    public function init()
    {
        parent::init();
        $this->on(Model::EVENT_AFTER_VALIDATE, function () {
            $this->requireEventNotFinish();
            $this->requireTimeReliable();
        });
    }

    public function rules()
    {
        return [
            [['event_id', 'identity_kind', 'city', 'title_from_provider', 'occur_at', 'provided_at'], 'required'],
            ['event_id', 'integer'],
            ['title_from_provider', 'string', 'length' => [4, 255]],
            ['city', 'string', 'length' => [2, 255]],

            ['identity_info', 'required', 'when' => function ($this) {
                return $this->identity_kind == LocationProvider::IDENTITY_KIND_PEOPLE;
            }, 'message' => 'Phone Number is required'],
            ['identity_info', 'match', 'pattern' => '/^1[34578]\d{9}$/', 'when' => function ($this) {
                return $this->identity_kind == LocationProvider::IDENTITY_KIND_PEOPLE;
            }, 'message' => 'Phone Number is invalid'],

            [['occur_at', 'provided_at'], 'date', 'format' => 'yyyy-MM-dd H:i'],
            ['identity_kind', 'in', 'range' => [
                LocationProvider::IDENTITY_KIND_POLICE,
                LocationProvider::IDENTITY_KIND_MONITOR_SYSTEM,
                LocationProvider::IDENTITY_KIND_PEOPLE,
            ]],
        ];
    }

    public function requireTimeReliable()
    {
        //时间可信度判断
        if (!$this->hasErrors()) {
            $event = $this->getEvent();
            if (!$event || $this->occur_at < $event->occur_at || $this->provided_at < $this->occur_at) {
                $this->addError('occur_at', 'The date provided is unreliable,please check it');
            }
        }
    }

    public function requireEventNotFinish()
    {
        if (!$this->hasErrors()) {
            $event = $this->getEvent();
            if (!$event || $event->is_finished) {
                $this->addError('event_id', 'This event has finished');
            }
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->createProvider();
            $this->createLocationNew();
            $transaction->commit();
            return true;
        } catch (\yii\db\Exception $e) {
            $this->addError('title', 'fail to create record in database');
        } catch (\yii\base\Exception $e) {
            $this->addError('title', $e->getMessage());
        }
        $transaction->rollBack();
        return false;
    }


    public function createProvider()
    {
        $provider = new LocationProvider();

        if ($this->identity_kind == LocationProvider::IDENTITY_KIND_PEOPLE) {
            $provider_exist = LocationProvider::findOne(['identity_info' => $this->identity_info]);
            if ($provider_exist) {
                $provider = $provider_exist;
            } else {
                $provider->identity_info = $this->identity_info;
            }
            $location = Yii::$app->telecoms->locateWithNumber($this->identity_info);
            if ($location) {
                $provider->latitude = $location['latitude'];
                $provider->longitude = $location['longitude'];
            } else {
                throw new TelecomsLocateFailException('fail to locate the provider with number : ' . $this->identity_info);
            }
        } elseif ($this->identity_kind == LocationProvider::IDENTITY_KIND_POLICE) {
            $provider->identity_info = LocationProvider::IDENTITY_KIND_POLICE . time();
        } else {
            $provider->identity_info = LocationProvider::IDENTITY_KIND_MONITOR_SYSTEM . time();
        }

        $provider->identity_kind = $this->identity_kind;
        $provider->provided_at = $this->provided_at;

        $provider->save();
        $this->_provider = $provider;
    }

    public function createLocationNew()
    {
        $location_new = new LocationNew();

        $location_new->attributes = [
            'user_id' => Yii::$app->user->id,
            'event_id' => $this->event_id,
            'provider_id' => $this->getProvider()->id,
            'city' => $this->city,
            'title_from_provider' => $this->title_from_provider,
            'occur_at' => $this->occur_at,
        ];
        $details = Yii::$app->map->searchWithTitle($this->city, $this->title_from_provider);
        if (!$details) {
            throw new MapWithTitleException('无法获取该位置准确经纬度,请修改位置信息重试');
        }
        $location_new->title_from_API = $details['title'];
        $location_new->latitude = $details['latitude'];
        $location_new->longitude = $details['longitude'];

        if ($this->identity_kind == LocationProvider::IDENTITY_KIND_PEOPLE) {
            $location_new->is_reliable = $this->isSpaceReliable();
        }

        $location_new->save();
        $this->_location_new = $location_new;
    }

    public function isSpaceReliable()
    {
        //空间可信度判断


        return true;
    }

    /**
     * @return Event|null
     */
    protected function getEvent()
    {
        if ($this->_event === null) {
            $this->_event = Event::findOne($this->event_id);
        }
        return $this->_event;
    }

    /**
     * @return LocationProvider
     */
    protected function getProvider()
    {
        return $this->_provider;
    }

    /**
     * @return LocationNew
     */
    protected function getLocationNew()
    {
        return $this->_location_new;
    }


}
