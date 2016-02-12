<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var frontend\modules\admin\models\LocationProviderSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="location-provider-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'identity_kind') ?>

		<?= $form->field($model, 'identity_info') ?>

		<?= $form->field($model, 'latitude') ?>

		<?= $form->field($model, 'longitude') ?>

		<?php // echo $form->field($model, 'provided_at') ?>

		<?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
