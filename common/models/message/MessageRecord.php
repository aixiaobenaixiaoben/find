<?php

namespace common\models\message;

use Yii;
use \common\models\message\base\MessageRecord as BaseMessageRecord;

/**
 * This is the model class for table "message_record".
 */
class MessageRecord extends BaseMessageRecord
{
    const RADIUS_UNDER_MILD = 1000;//meters
    const RADIUS_UNDER_URGENT = 2000;
    const RADIUS_UNDER_EMERGENCY = 3000;

    public static function getNewNumbers($event_id, $new_numbers)
    {
        $notifiedNumbers = (new \yii\db\Query())
            ->select('number')
            ->from(self::tableName())
            ->where('event_id=:event_id', [':event_id' => $event_id])
            ->all();
        $index = 0;
        $recordedNumbers = [];
        foreach ($notifiedNumbers as $notifiedNumber) {
            $recordedNumbers[$index++] = $notifiedNumber['number'];
        }

        return array_diff($new_numbers, $recordedNumbers);
    }

    public static function createRecords($event_id, $new_numbers)
    {
        $user_id = Yii::$app->user->id;
        foreach ($new_numbers as $new_number) {
            $record = new self();
            $record->attributes = [
                'user_id' => $user_id,
                'number' => $new_number,
                'event_id' => $event_id,
            ];
            $record->save();
        }
    }


}
