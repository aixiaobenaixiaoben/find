<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var common\models\location\LocationProvider $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="location-provider-form">

    <?php $form = ActiveForm::begin([
    'id' => 'LocationProvider',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
    );
    ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>
            
			<?= $form->field($model, 'id')->textInput() ?>
			<?=                         $form->field($model, 'identity_kind')->dropDownList(
                            common\models\location\LocationProvider::optsidentitykind()
                        ); ?>
			<?= $form->field($model, 'identity_info')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'provided_at')->textInput() ?>
			<?= $form->field($model, 'created_at')->textInput() ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'LocationProvider',
    'content' => $this->blocks['main'],
    'active'  => true,
], ]
                 ]
    );
    ?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?= Html::submitButton(
        '<span class="glyphicon glyphicon-check"></span> ' .
        ($model->isNewRecord ? 'Create' : 'Save'),
        [
        'id' => 'save-' . $model->formName(),
        'class' => 'btn btn-success'
        ]
        );
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>

