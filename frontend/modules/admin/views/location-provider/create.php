<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\location\LocationProvider $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Location Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud location-provider-create">

    <h1>
        <?= 'Location Provider' ?>        <small>
                        <?= $model->id ?>        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
            'Cancel',
            \yii\helpers\Url::previous(),
            ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
