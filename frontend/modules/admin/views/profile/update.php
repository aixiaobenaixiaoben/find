<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\profile\Profile $model
*/

$this->title = 'Profile ' . $model->name . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud profile-update">

    <h1>
        <?= 'Profile' ?>        <small>
                        <?= $model->name ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
