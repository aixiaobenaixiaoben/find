<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'user/index/index',

    'modules' => [
        'admin' => [
            'class' => 'frontend\modules\admin\Module',
        ],
        'find' => [
            'class' => 'frontend\modules\find\Module',
        ],
        'user' => [
            'class' => 'frontend\modules\user\Module',
        ],

    ],

    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
//            'suffix' => '.html',//设置URL后缀
            'rules' => [
//                '<id:\d+>' => 'index/view',
//                '<controller:[-\w]+>/<id:\d+>' => '<controller>/view',
//                '<controller:[-\w]+>/<action:[-\w]+>' => '<controller>/<action>',
//                '<controller:[-\w]+>/<action:[-\w]+>/<id:\d+>' => '<controller>/<action>',
                '<module:[-\w]+>/<controller:[-\w]+>/<action:[-\w]+>' => '<module>/<controller>/<action>',
            ],
        ],
        'view' => [
            'title' => 'Find',
            'defaultExtension' => 'tpl',
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    //'cachePath' => '@runtime/Smarty/cache',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'user/index/error',
        ],
    ],
    'params' => $params,
];
