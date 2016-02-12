<?php

namespace \api;

/**
* This is the class for REST controller "LocationProviderController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class LocationProviderController extends \yii\rest\ActiveController
{
public $modelClass = 'common\models\location\LocationProvider';
}
