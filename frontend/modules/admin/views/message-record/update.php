<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\message\MessageRecord $model
*/

$this->title = 'Message Record ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Message Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud message-record-update">

    <h1>
        <?= 'Message Record' ?>        <small>
                        <?= $model->id ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
