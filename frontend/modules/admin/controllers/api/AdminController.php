<?php

namespace \api;

/**
* This is the class for REST controller "AdminController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class AdminController extends \yii\rest\ActiveController
{
public $modelClass = 'common\models\admin\Admin';
}
