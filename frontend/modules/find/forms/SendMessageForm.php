<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/13
 * Time: 13:51
 */
namespace frontend\modules\find\forms;

use common\models\event\Event;
use common\models\location\LocationCurrent;
use common\models\message\MessageRecord;
use common\models\profile\Profile;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class SendMessageForm extends Model
{

    /** @var  String *儿童姓名 */
    public $name;
    /** @var  Integer *儿童年龄 */
    public $age;
    /** @var  Enum *性别,限于(male,female) */
    public $gender;
    /** @var  Integer *身高 */
    public $height;
    /** @var  String *衣着 */
    public $dress;
    /** @var  String *外贸描述(选填) */
    public $appearance;

    /** @var  Integer *事件ID */
    public $event_id;
    /** @var  Integer *涉事儿童信息ID */
    public $profile_id;
    /** @var  Integer *当前节点ID */
    public $location_current_id;
    /** @var  String *事件发生城市 */
    public $city;
    /** @var  String *事件发生详细地点 */
    public $location;

    private $_event = null;
    private $_profile = null;
    private $_current = null;

    public function init()
    {
        parent::init();
        $this->on(Model::EVENT_AFTER_VALIDATE, function () {
            $this->requireResourceAvailable();
        });
    }

    public function rules()
    {
        return [
            [['name', 'age', 'gender', 'height', 'dress', 'event_id', 'profile_id', 'location_current_id', 'city', 'location'], 'required'],
            [['name', 'city'], 'string', 'length' => [2, 40]],
            [['dress', 'appearance', 'location'], 'string', 'length' => [2, 255]],
            [['age', 'height', 'event_id', 'profile_id', 'location_current_id'], 'integer'],
            ['gender', 'in', 'range' => [
                Profile::GENDER_MALE,
                Profile::GENDER_FEMALE,
            ]],
        ];
    }

    public function requireResourceAvailable()
    {
        if (!$this->hasErrors()) {
            $event = $this->getEvent();
            $profile = $this->getProfile();
            $current = $this->getLocationCurrent();

            if (!$event || $event->is_finished || !$profile || !$current) {
                $this->addError('resource', 'No available resource');
            }
        }
    }

    public function save()
    {

        if (!$this->validate()) return false;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->refreshProfile();
            $current = $this->getLocationCurrent();
            $event = $this->getEvent();

            $radius = MessageRecord::RADIUS_UNDER_MILD;
            if ($event->urgent == Event::URGENT_URGENT) {
                $radius = MessageRecord::RADIUS_UNDER_URGENT;
            } elseif ($event->urgent == Event::URGENT_EMERGENCY) {
                $radius = MessageRecord::RADIUS_UNDER_EMERGENCY;
            }

            $numbers = Yii::$app->telecoms->getNumbers($current->latitude, $current->longitude, $radius);
            $newNumbers = MessageRecord::getNewNumbers($event->id, $numbers);

            if (!$newNumbers) {
                throw new Exception("No new numbers found");
            }

            Yii::$app->telecoms->sendMessage($newNumbers, $this);
            MessageRecord::createRecords($event->id, $newNumbers);

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

    /**
     * @return Profile|null
     */
    public function refreshProfile()
    {

        $profile = $this->getProfile();
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
        return $profile;
    }

    /**
     * @return null|Event
     */
    public function getEvent()
    {
        if (!$this->_event) {
            $this->_event = Event::findOne($this->event_id);
        }
        return $this->_event;
    }

    /**
     * @return null|Profile
     */
    public function getProfile()
    {
        if (!$this->_profile) {
            $this->_profile = Profile::findOne($this->profile_id);
        }
        return $this->_profile;
    }

    /**
     * @return null|LocationCurrent
     */
    public function getLocationCurrent()
    {
        if (!$this->_current) {
            $this->_current = LocationCurrent::findOne($this->location_current_id);
        }
        return $this->_current;
    }


}
