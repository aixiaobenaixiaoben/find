<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/14
 * Time: 23:07
 */
namespace common\components;

use yii\base\Component;

class Telecoms extends Component
{
    public function locateWithNumber($number)
    {
        return [
            'latitude' => 110.1234567,
            'longitude' => 45.66666,
        ];
    }

    public function sendMessageWithinArea($latitude, $longitude, $radius, $message)
    {
        return true;
    }
}