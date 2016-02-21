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
    public $identity_kind;
    public $title_from_provider;
    public $occur_at;
    public $provided_at;

    public $identity_info;

    /** @var  Event */
    private $_event;
    /** @var  LocationProvider */
    private $_provider;
    /** @var  LocationNew */
    private $_location_new;

    public function rules()
    {
        return [
            [['event_id', 'identity_kind', 'title_from_provider', 'occur_at', 'provided_at'], 'required'],
            ['event_id', 'integer'],
            [['title_from_provider', 'identity_info'], 'string', 'length' => [4, 255]],

            ['identity_info', 'match', 'pattern' => '/^1[34578]\d{9}$/', 'when' => function ($this) {
                return $this->identity_kind == LocationProvider::IDENTITY_KIND_PEOPLE;
            }],

            [['occur_at', 'provided_at'], 'date', 'format' => 'yyyy-MM-dd H:i:s'],
            ['identity_kind', 'in', 'range' => [
                LocationProvider::IDENTITY_KIND_POLICE,
                LocationProvider::IDENTITY_KIND_MONITOR_SYSTEM,
                LocationProvider::IDENTITY_KIND_PEOPLE,
            ]],
        ];
    }

    public function save()
    {
        if (!$this->validate()) return false;

        $event = Event::findOne($this->event_id);
        if ($event == null) {
            $this->addError('title', 'No event found with id : ' . $this->event_id);
            return false;
        }
        $this->_event = $event;

        $transaction = Yii::$app->db->beginTransaction();
        try {
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
            'provider_id' => $this->_provider->id,
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

        if ($this->identity_kind == LocationProvider::IDENTITY_KIND_PEOPLE) {
            $location_new->is_reliable = $this->isNewLocationReliable($location_new);
        }

        $location_new->save();
        $this->_location_new = $location_new;
    }

    public function isNewLocationReliable($location_new)
    {
        //空间可信度判断


        //时间可信度判断
        return $this->_event->occur_at <= $this->occur_at && $this->occur_at <= $this->provided_at;
    }


}
