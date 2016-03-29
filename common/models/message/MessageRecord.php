<?php

namespace common\models\message;

use Yii;
use \common\models\message\base\MessageRecord as BaseMessageRecord;

/**
 * This is the model class for table "message_record".
 */
class MessageRecord extends BaseMessageRecord
{
    public static function getNewNumbers($event_id, $new_numbers)
    {
        $notifiedNumbers = (new \yii\db\Query())
            ->select('number')
            ->from(self::tableName())
            ->where(['event_id=:event_id'], [':event_id' => $event_id])
            ->all();
        return array_diff($new_numbers, $notifiedNumbers);
    }

    public static function createRecords($event_id, $new_numbers)
    {
        foreach ($new_numbers as $new_number) {
            $record = new self();
            $record->attributes = [
                'user_id' => Yii::$app->user->id,
                'number' => $new_number,
                'event_id' => $event_id,
            ];
            $record->save();
        }
    }


}
