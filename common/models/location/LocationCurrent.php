<?php

namespace common\models\location;

use Carbon\Carbon;
use Yii;
use \common\models\location\base\LocationCurrent as BaseLocationCurrent;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "location_current".
 */
class LocationCurrent extends BaseLocationCurrent
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function () {
                    return Carbon::now()->format('Y-m-d H:i:s');
                }
            ],
        ];
    }

    public static function createCurrent($event)
    {
        /** @var  LocationNew */
        $location_new = $event->sender;
        $current = new self();
        $current->attributes = [
//            'user_id'=>Yii::$app->user->id,
            'user_id' => 1,
            'event_id' => $location_new->event_id,
            'title' => $location_new->title_from_API,
            'longitude' => $location_new->longitude,
            'latitude' => $location_new->latitude,
            'occur_at' => $location_new->occur_at,
        ];

        $exist_currents = $location_new->event->locationCurrents;
        if ($exist_currents == null) $current->is_origin = 1;

        if (!$current->save()) {
            throw new Exception('fail to create location current', $current->errors);
        }
        return true;
    }
}
