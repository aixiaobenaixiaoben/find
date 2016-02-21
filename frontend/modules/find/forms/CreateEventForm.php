<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/13
 * Time: 13:51
 */
namespace frontend\modules\find\forms;

use common\exceptions\MapWithTitleException;
use common\models\event\Event;
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

    private $_event;
    private $_provider;
    private $_location_new;

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
            $transaction->commit();
            return true;
        } catch (\yii\base\Exception $e) {
            $transaction->rollBack();

            if ($e instanceof \yii\db\Exception) {
                $this->addError('title', 'fail to create record in database');
            } else {
                $this->addError('title', $e->getMessage());
            }
            return false;
        }
    }

    public function createEvent()
    {
        $event = new Event();
        $event->attributes = [
            'user_id' => Yii::$app->user->id,
            'theme' => $this->theme,
            'description' => $this->description,
            'urgent' => $this->urgent,
            'occur_at' => $this->occur_at,
        ];

        $event->save();
        $this->_event = $event;
    }

    public function createProvider()
    {
        $provider = new LocationProvider();
        $provider->identity_info = LocationProvider::IDENTITY_KIND_POLICE . time();
        $provider->save();
        $this->_provider = $provider;
    }

    public function createLocationNew()
    {
        $location_new = new LocationNew();

        $location_new->attributes = [
            'user_id' => Yii::$app->user->id,
            'event_id' => $this->getEvent()->id,
            'provider_id' => $this->getProvider()->id,
            'title_from_provider' => $this->title_from_provider,
            'occur_at' => $this->occur_at,
        ];
        $details = Yii::$app->map->searchWithTitle($this->title_from_provider);
        if (!$details) {
            throw new MapWithTitleException('fail to search out the place with title from provider : ' . $this->title_from_provider);
        }
        $location_new->title_from_API = $details['title'];
        $location_new->latitude = $details['latitude'];
        $location_new->longitude = $details['longitude'];

        $location_new->save();
        $this->_location_new = $location_new;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->_event;
    }

    /**
     * @return LocationProvider
     */
    public function getProvider()
    {
        return $this->_provider;
    }

    /**
     * @return LocationNew
     */
    public function getLocationNew()
    {
        return $this->_location_new;
    }

}
