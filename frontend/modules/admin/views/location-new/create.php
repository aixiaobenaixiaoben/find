<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\location\LocationNew $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Location News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud location-new-create">

    <h1>
        <?= 'Location New' ?>        <small>
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
