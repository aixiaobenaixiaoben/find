<?php

namespace common\models\profile;

use Carbon\Carbon;
use Yii;
use \common\models\profile\base\Profile as BaseProfile;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 */
class Profile extends BaseProfile
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
