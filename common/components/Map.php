<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/13
 * Time: 23:51
 */
namespace common\components;

use yii\base\Component;

class Map extends Component
{
    public function searchWithTitle($title)
    {
        return [
            'title' => '外滩十八号',
            'latitude' => 110.1234567,
            'longitude' => 45.66666,
        ];
    }

    public function searchWithLatitude($latitude, $longitude)
    {
        return '外滩十八号';
    }
}