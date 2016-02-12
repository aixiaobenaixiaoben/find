<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\location\LocationCurrent $model
*/

$this->title = 'Location Current ' . $model->title . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Location Currents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud location-current-update">

    <h1>
        <?= 'Location Current' ?>        <small>
                        <?= $model->title ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
