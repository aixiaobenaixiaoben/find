<?php

namespace common\models\location;

use Carbon\Carbon;
use common\models\event\Event;
use Yii;
use \common\models\location\base\LocationCurrent as BaseLocationCurrent;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "location_current".
 */
class LocationCurrent extends BaseLocationCurrent
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function () {
                    return Carbon::now()->format('Y-m-d H:i:s');
                }
            ],
        ];
    }

    public static function createCurrent($event)
    {
        /** @var LocationNew $location_new */
        $location_new = $event->sender;

        if ($location_new->provider->identity_kind == LocationProvider::IDENTITY_KIND_PEOPLE && !$location_new->is_reliable) return false;
        $current = new self();
        $current->attributes = [
            'user_id' => Yii::$app->user->id,
            'event_id' => $location_new->event_id,
            'title' => $location_new->title_from_API,
            'longitude' => $location_new->longitude,
            'latitude' => $location_new->latitude,
            'occur_at' => $location_new->occur_at,
        ];

        $exist_currents = $location_new->event->locationCurrents;
        if ($exist_currents == null) $current->is_origin = 1;

        $current->save();
        self::updateMap($current->event_id);
        return true;
    }

    /**
     * @param $event_id
     * @return bool
     */
    public static function updateMap($event_id)
    {

        /** @var Event $event */
        $event = Event::find()
            ->with(['locationCurrents' => function ($query) {
                $query->orderBy('occur_at ASC');
            }])
            ->where('id=:event_id and is_finished=:is_finished', [
                'event_id' => $event_id,
                'is_finished' => 0
            ])
            ->one();
        if (!$event || !isset($event->locationCurrents[0])) return false;

        $content = '
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <title>' . $event->theme . '</title>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=gZUugrfkf5TKwsGQmxEsliqL"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/CurveLine/1.5/src/CurveLine.min.js"></script>
    <style type="text/css">
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: Microsoft YaHei, monospace
        }
    </style>
</head>
<body>
<div style="width:100%;height:100%;border:1px solid gray" id="container"></div>
</body>
</html>';


        $content .= '
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("container");
    map.centerAndZoom(new BMap.Point(' . $event->locationCurrents[0]->longitude . ',' . $event->locationCurrents[0]->latitude . '), 14);
    map.enableScrollWheelZoom();
    var points = [';


        $first = true;
        foreach ($event->locationCurrents as $current) {
            if ($first) {
                $first = false;
            } else {
                $content .= ',';
            }
            $content .= 'new BMap.Point(' . $current->longitude . ', ' . $current->latitude . ')';
        }

        $content .= '
];
    var curve = new BMapLib.CurveLine(points, {strokeColor: "blue", strokeWeight: 3, strokeOpacity: 0.5}); //创建弧线对象
    map.addOverlay(curve); //添加到地图中
    curve.enableEditing(); //开启编辑功能
</script>';


        $name = substr(hash('md5', $event_id), 0, 10);
        $createMap = file_put_contents("/alidata/www/default/maps/$name.html", $content);
        if (!$createMap) return false;

        return true;

    }
}
