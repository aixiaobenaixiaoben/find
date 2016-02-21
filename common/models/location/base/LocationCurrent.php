<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\location\base;

use Yii;

/**
 * This is the base-model class for table "location_current".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $event_id
 * @property integer $is_origin
 * @property string $title
 * @property string $latitude
 * @property string $longitude
 * @property string $occur_at
 * @property string $created_at
 *
 * @property \common\models\event\Event $event
 * @property \common\models\User $user
 */
abstract class LocationCurrent extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location_current';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'event_id', 'title', 'latitude', 'longitude'], 'required'],
            [['user_id', 'event_id'], 'integer'],
            ['is_origin', 'boolean'],
            [['latitude', 'longitude'], 'number'],
            [['occur_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
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
            'is_origin' => 'Is Origin',
            'title' => 'Title',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'occur_at' => 'Occur At',
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
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }


}
