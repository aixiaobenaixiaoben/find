<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\message\MessageRecord $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Message Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud message-record-create">

    <h1>
        <?= 'Message Record' ?>        <small>
                        <?= $model->id ?>        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
            'Cancel',
            \yii\helpers\Url::previous(),
            ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
