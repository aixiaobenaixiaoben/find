<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\location\LocationCurrent $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Location Currents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud location-current-create">

    <h1>
        <?= 'Location Current' ?>        <small>
                        <?= $model->title ?>        </small>
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
