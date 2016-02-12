<?php

namespace \api;

/**
* This is the class for REST controller "LocationNewController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class LocationNewController extends \yii\rest\ActiveController
{
public $modelClass = 'common\models\location\LocationNew';
}
