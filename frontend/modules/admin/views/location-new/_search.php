<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var frontend\modules\admin\models\LocationNewSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="location-new-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'event_id') ?>

		<?= $form->field($model, 'provider_id') ?>

		<?= $form->field($model, 'title_from_provider') ?>

		<?php // echo $form->field($model, 'title_from_API') ?>

		<?php // echo $form->field($model, 'latitude') ?>

		<?php // echo $form->field($model, 'longitude') ?>

		<?php // echo $form->field($model, 'is_reliable') ?>

		<?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
