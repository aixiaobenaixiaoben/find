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


    public function searchWithTitle($city, $title_from_provider)
    {
        $map_api_key = 'GBbbBdH8xGtu5Zhyf9s49zsp';
        $data = file_get_contents("http://api.map.baidu.com/place/v2/suggestion?query={$title_from_provider}&region={$city}&output=json&ak={$map_api_key}");
        $data = json_decode($data);
        if (!isset($data->result[0]) || !isset($data->result[0]->location)) return null;
        return [
            'title' => $data->result[0]->name,
            'latitude' => $data->result[0]->location->lat,
            'longitude' => $data->result[0]->location->lng,
        ];
    }

    public function searchWithLatitude($latitude, $longitude)
    {
        return '外滩十八号';
    }
}