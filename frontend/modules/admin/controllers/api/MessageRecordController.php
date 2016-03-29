<?php

namespace \api;

/**
* This is the class for REST controller "MessageRecordController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class MessageRecordController extends \yii\rest\ActiveController
{
public $modelClass = 'common\models\message\MessageRecord';
}
