<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/15
 * Time: 21:36
 */
namespace common\models\behaviors;

use common\models\location\LocationNew;
use yii\base\Behavior;

class LocationBehavior extends Behavior
{
    public function events()
    {
        return [
            LocationNew::EVENT_AFTER_INSERT => ['common\models\location\LocationCurrent', 'createCurrent'],
        ];
    }

}