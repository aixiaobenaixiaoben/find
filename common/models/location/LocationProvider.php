<?php

namespace common\models\location;

use Carbon\Carbon;
use Yii;
use \common\models\location\base\LocationProvider as BaseLocationProvider;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "location_provider".
 */
class LocationProvider extends BaseLocationProvider
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
            ]
        ];
    }

}
