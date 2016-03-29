<?php

namespace common\models\event;

use Carbon\Carbon;
use Yii;
use \common\models\event\base\Event as BaseEvent;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "event".
 */
class Event extends BaseEvent
{
    const VIEW_ROUTE_ON_MAP = 1;
    const VIEW_CURRENT_ON_MAP = 2;
    const VIEW_ON_MAP = 3;

    const ZOOM_SIZE_FOR_ROUTE = 14;
    const ZOOM_SIZE_FOR_CURRENT = 16;
    const ZOOM_SIZE_FOR_VIEW = 15;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function () {
                    return Carbon::now()->format('Y-m-d H:i:s');
                }
            ]
        ];
    }


}
