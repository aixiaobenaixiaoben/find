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
use common\models\profile\Profile;
use Yii;
use yii\base\Model;

class CreateEventForm extends Model
{
    public $name;
    public $age;
    public $gender;
    public $height;
    public $dress;
    public $appearance;

    public $theme;
    public $description;
    public $urgent;
    public $occur_at;
    public $city;
    public $title_from_provider;

    private $_event;
    private $_profile;
    private $_provider;
    private $_location_new;

    public function rules()
    {
        return [
            [['name', 'age', 'gender', 'height', 'dress', 'theme', 'urgent', 'city', 'title_from_provider', 'occur_at'], 'required'],
            [['dress', 'appearance', 'theme', 'description', 'title_from_provider'], 'string', 'length' => [4, 255]],
            [['name','city'], 'string', 'length' => [2, 40]],
            [['age', 'height'], 'integer'],
            ['occur_at', 'date', 'format' => 'yyyy-MM-dd H:i'],
            ['urgent', 'in', 'range' => [
                Event::URGENT_MILD,
                Event::URGENT_URGENT,
                Event::URGENT_EMERGENCY,
            ]],
            ['gender', 'in', 'range' => [
                Profile::GENDER_MALE,
                Profile::GENDER_FEMALE,
            ]],
        ];
    }

    public function save()
    {
        if (!$this->validate()) return false;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->createEvent();
            $this->createProfile();
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

    public function createEvent()
    {
        $event = new Event();
        $event->attributes = [
            'user_id' => Yii::$app->user->id,
            'city' => $this->city,
            'theme' => $this->theme,
            'description' => $this->description,
            'urgent' => $this->urgent,
            'occur_at' => $this->occur_at,
        ];

        $event->save();
        $this->_event = $event;
    }

    public function createProfile()
    {
        $profile = new Profile();
        $profile->attributes = [
            'event_id' => $this->getEvent()->id,
            'name' => $this->name,
            'age' => $this->age,
            'gender' => $this->gender,
            'height' => $this->height,
            'dress' => $this->dress,
            'appearance' => $this->appearance,
        ];
        $profile->save();
        $this->_profile = $profile;
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
     * @return Profile
     */
    public function getProfile()
    {
        return $this->_profile;
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
