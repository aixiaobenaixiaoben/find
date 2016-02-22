<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var frontend\modules\admin\models\UserSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'username') ?>

		<?= $form->field($model, 'email') ?>

		<?= $form->field($model, 'auth_key') ?>

		<?= $form->field($model, 'password_hash') ?>

		<?php // echo $form->field($model, 'dynamic_key') ?>

		<?php // echo $form->field($model, 'dynamic_key_expired_at') ?>

		<?php // echo $form->field($model, 'is_blocked') ?>

		<?php // echo $form->field($model, 'is_activated') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
