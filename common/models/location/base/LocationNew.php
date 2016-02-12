<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\location\base;

use Yii;

/**
 * This is the base-model class for table "location_new".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $event_id
 * @property integer $provider_id
 * @property string $title_from_provider
 * @property string $title_from_API
 * @property string $latitude
 * @property string $longitude
 * @property integer $is_reliable
 * @property string $created_at
 *
 * @property \common\models\event\Event $event
 * @property \common\models\location\LocationProvider $provider
 * @property \common\models\user\User $user
 */
abstract class LocationNew extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location_new';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'event_id', 'provider_id', 'title_from_provider', 'title_from_API', 'latitude', 'longitude'], 'required'],
            [['user_id', 'event_id', 'provider_id', 'is_reliable'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['created_at'], 'safe'],
            [['title_from_provider', 'title_from_API'], 'string', 'max' => 255],
            [['event_id', 'provider_id', 'latitude', 'longitude'], 'unique', 'targetAttribute' => ['event_id', 'provider_id', 'latitude', 'longitude'], 'message' => 'The combination of Event ID, Provider ID, Latitude and Longitude has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'event_id' => 'Event ID',
            'provider_id' => 'Provider ID',
            'title_from_provider' => 'Title From Provider',
            'title_from_API' => 'Title From  Api',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'is_reliable' => 'Is Reliable',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(\common\models\event\Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(\common\models\location\LocationProvider::className(), ['id' => 'provider_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\user\User::className(), ['id' => 'user_id']);
    }


}
