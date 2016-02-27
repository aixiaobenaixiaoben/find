<?php

namespace common\models\event;

use Carbon\Carbon;
use Yii;
use \common\models\event\base\Event as BaseEvent;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "event".
 */
class Event extends BaseEvent
{
    const VIEW_ROUTE_ON_MAP = 1;
    const VIEW_CURRENT_ON_MAP = 2;
    const VIEW_ON_MAP = 3;

    const ZOOM_SIZE_FOR_ROUTE = 14;
    const ZOOM_SIZE_FOR_CURRENT = 16;
    const ZOOM_SIZE_FOR_VIEW = 15;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function () {
                    return Carbon::now()->format('Y-m-d H:i:s');
                }
            ]
        ];
    }


    public static function createRouteMap($id)
    {
        /** @var Event $event */
        $event = Event::find()
            ->with(['locationCurrents' => function ($query) {
                $query->orderBy('occur_at ASC');
            }])
            ->where('id=:event_id and is_finished=:is_finished', [
                'event_id' => $id,
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


        $createMap = file_put_contents("/alidata/www/default/find.route.html", $content);
        if (!$createMap) return false;

        return true;
    }


}
