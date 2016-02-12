<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\location\LocationProvider $model
*/

$this->title = 'Location Provider ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Location Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud location-provider-update">

    <h1>
        <?= 'Location Provider' ?>        <small>
                        <?= $model->id ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
