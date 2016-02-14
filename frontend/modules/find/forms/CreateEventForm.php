<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/13
 * Time: 13:51
 */
namespace frontend\modules\find\forms;

use Carbon\Carbon;
use common\exceptions\MapWithTitleException;
use common\models\event\Event;
use common\models\location\LocationCurrent;
use common\models\location\LocationNew;
use common\models\location\LocationProvider;
use Yii;
use yii\base\Model;
use yii\db\Exception;

class CreateEventForm extends Model
{
    public $theme;
    public $description;
    public $urgent;
    public $occur_at;
    public $title_from_provider;

    /** @var  Event */
    private $_event;
    /** @var  LocationProvider */
    private $_provider;
    /** @var  LocationNew */
    private $_location_new;
    /** @var  LocationCurrent */
    private $_location_current;

    public function rules()
    {
        return [
            [['theme', 'urgent', 'title_from_provider'], 'required'],
            [['theme', 'description', 'title_from_provider'], 'string', 'length' => [4, 255]],
            ['occur_at', 'date', 'format' => 'yyyy-MM-dd H:i:s'],
            ['urgent', 'in', 'range' => [
                Event::URGENT_MILD,
                Event::URGENT_URGENT,
                Event::URGENT_EMERGENCY,
            ]],
        ];
    }

    public function save()
    {
        if (!$this->validate()) return false;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->createEvent();
            $this->createProvider();
            $this->createLocationNew();
            $this->createCurrent();
            $transaction->commit();
            return true;
        } catch (\yii\base\Exception $e) {
            $this->addError('title', $e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }

    public function createEvent()
    {
        $event = new Event();
        $event->attributes = [
//            'user_id' => Yii::$app->user->id,
            'user_id' => 3,
            'theme' => $this->theme,
            'description' => $this->description,
            'urgent' => $this->urgent,
            'occur_at' => $this->occur_at,
            'created_at' => Carbon::now()
        ];

        if ($event->save()) {
            $this->_event = $event;
            return true;
        } else {
            throw new Exception($event->errors);
        }
    }

    public function createProvider()
    {
        $provider = new LocationProvider();
        $provider->identity_info = LocationProvider::IDENTITY_KIND_POLICE . time();
        if ($provider->save()) {
            $this->_provider = $provider;
            return true;
        } else throw new Exception($provider->errors);
    }

    public function createLocationNew()
    {
        $location_new = new LocationNew();

        $location_new->attributes = [
//            'user_id' => Yii::$app->user->id,
            'user_id' => 3,
            'event_id' => $this->_event->id,
            'provider_id' => $this->_provider->id,
            'title_from_provider' => $this->title_from_provider,
            'created_at' => Carbon::now(),
        ];
        $details = Yii::$app->map->searchWithTitle($this->title_from_provider);
        if (!$details) {
            throw new MapWithTitleException('fail to search out the place with title from provider : ' . $this->title_from_provider);
        }
        $location_new->title_from_API = $details['title'];
        $location_new->latitude = $details['latitude'];
        $location_new->longitude = $details['longitude'];

        if ($location_new->save()) {
            $this->_location_new = $location_new;
            return true;
        } else {
            throw new Exception($location_new->errors);
        }
    }

    public function createCurrent()
    {
        $current = new LocationCurrent();
        $current->attributes = [
//            'user_id'=>Yii::$app->user->id,
            'user_id' => 3,
            'event_id' => $this->_event->id,
            'is_origin' => 1,
            'title' => $this->_location_new->title_from_API,
            'longitude' => $this->_location_new->longitude,
            'latitude' => $this->_location_new->latitude,
            'occur_at' => $this->occur_at,
            'created_at' => Carbon::now(),
        ];
        if ($current->save()) {
            $this->_location_current = $current;
            return true;
        } else {
            throw new Exception($current->errors);
        }
    }


    public function getEvent()
    {
        return $this->_event;
    }

    public function getProvider()
    {
        return $this->_provider;
    }

    public function getLocationNew()
    {
        return $this->_location_current;
    }

    public function getLocationCurrent()
    {
        return $this->_location_current;
    }

}
