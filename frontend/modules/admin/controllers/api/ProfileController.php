<?php

namespace \api;

/**
* This is the class for REST controller "ProfileController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ProfileController extends \yii\rest\ActiveController
{
public $modelClass = 'common\models\profile\Profile';
}
