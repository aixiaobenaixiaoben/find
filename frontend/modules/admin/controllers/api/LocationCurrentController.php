<?php

namespace \api;

/**
* This is the class for REST controller "LocationCurrentController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class LocationCurrentController extends \yii\rest\ActiveController
{
public $modelClass = 'common\models\location\LocationCurrent';
}
