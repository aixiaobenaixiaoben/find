<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var frontend\modules\admin\models\UserSearch $searchModel
 */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="giiant-crud user-index">

    <?php //             echo $this->render('_search', ['model' =>$searchModel]);
    ?>


    <?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

    <h1>
        <?= 'Users' ?>
        <small>
            List
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New', ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">


            <?=
            \yii\bootstrap\ButtonDropdown::widget(
                [
                    'id' => 'giiant-relations',
                    'encodeLabel' => false,
                    'label' => '<span class="glyphicon glyphicon-paperclip"></span> ' . 'Relations',
                    'dropdown' => [
                        'options' => [
                            'class' => 'dropdown-menu-right'
                        ],
                        'encodeLabels' => false,
                        'items' => [[
                            'url' => ['admin/index'],
                            'label' => '<i class="glyphicon glyphicon-arrow-right">&nbsp;' . 'Admin' . '</i>',
                        ], [
                            'url' => ['event/index'],
                            'label' => '<i class="glyphicon glyphicon-arrow-right">&nbsp;' . 'Event' . '</i>',
                        ], [
                            'url' => ['location-current/index'],
                            'label' => '<i class="glyphicon glyphicon-arrow-right">&nbsp;' . 'Location Current' . '</i>',
                        ], [
                            'url' => ['location-new/index'],
                            'label' => '<i class="glyphicon glyphicon-arrow-right">&nbsp;' . 'Location New' . '</i>',
                        ],]
                    ],
                    'options' => [
                        'class' => 'btn-default'
                    ]
                ]
            );
            ?>        </div>
    </div>


    <div class="table-responsive">
        <?= GridView::widget([
            'layout' => '{summary}{pager}{items}{pager}',
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last'],
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [

                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string)$key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
                'id',
                'username',
                'email',
//			'auth_key',
//			'password_hash',
                'is_blocked',
                'is_activated',
                'dynamic_key_expired_at',
                'created_at',
                'updated_at',
                /*'dynamic_key'*/
            ],
        ]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>



