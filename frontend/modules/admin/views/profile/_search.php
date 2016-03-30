<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var frontend\modules\admin\models\ProfileSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="profile-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'event_id') ?>

		<?= $form->field($model, 'name') ?>

		<?= $form->field($model, 'age') ?>

		<?= $form->field($model, 'gender') ?>

		<?php // echo $form->field($model, 'height') ?>

		<?php // echo $form->field($model, 'dress') ?>

		<?php // echo $form->field($model, 'appearance') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
