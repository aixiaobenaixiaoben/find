<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var frontend\modules\admin\models\LocationCurrentSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="location-current-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'event_id') ?>

		<?= $form->field($model, 'is_origin') ?>

		<?= $form->field($model, 'title') ?>

		<?php // echo $form->field($model, 'latitude') ?>

		<?php // echo $form->field($model, 'longitude') ?>

		<?php // echo $form->field($model, 'occur_at') ?>

		<?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
