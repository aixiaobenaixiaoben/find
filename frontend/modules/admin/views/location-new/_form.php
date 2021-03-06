<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var common\models\location\LocationNew $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="location-new-form">

    <?php $form = ActiveForm::begin([
    'id' => 'LocationNew',
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
			<?= // generated by schmunk42\giiant\generators\crud\providers\RelationProvider::activeField
$form->field($model, 'user_id')->dropDownList(
    \yii\helpers\ArrayHelper::map(common\models\User::find()->all(), 'id', 'id'),
    ['prompt' => 'Select']
); ?>
			<?= // generated by schmunk42\giiant\generators\crud\providers\RelationProvider::activeField
$form->field($model, 'event_id')->dropDownList(
    \yii\helpers\ArrayHelper::map(common\models\event\Event::find()->all(), 'id', 'id'),
    ['prompt' => 'Select']
); ?>
			<?= // generated by schmunk42\giiant\generators\crud\providers\RelationProvider::activeField
$form->field($model, 'provider_id')->dropDownList(
    \yii\helpers\ArrayHelper::map(common\models\location\LocationProvider::find()->all(), 'id', 'id'),
    ['prompt' => 'Select']
); ?>
			<?= $form->field($model, 'title_from_provider')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'title_from_API')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'is_reliable')->textInput() ?>
			<?= $form->field($model, 'created_at')->textInput() ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'LocationNew',
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

