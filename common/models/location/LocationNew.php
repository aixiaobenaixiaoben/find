<?php

namespace common\models\location;

use Carbon\Carbon;
use common\models\behaviors\LocationBehavior;
use Yii;
use \common\models\location\base\LocationNew as BaseLocationNew;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "location_new".
 */
class LocationNew extends BaseLocationNew
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
            'locationBehavior' => [
                'class' => LocationBehavior::className()
            ]
        ];
    }
}
