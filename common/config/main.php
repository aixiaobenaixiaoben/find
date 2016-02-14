<?php
return [
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'map' => [
            'class' => 'common\components\Map',
        ],
        'Telecoms' => [
            'class' => 'common\components\Telecoms',
        ],
    ],
];
