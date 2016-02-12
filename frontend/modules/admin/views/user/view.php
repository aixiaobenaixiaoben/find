<?php

use dmstr\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var common\models\User $model
*/
$copyParams = $model->attributes;

$this->title = 'User ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud user-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= 'User' ?>        <small>
            <?= $model->id ?>        </small>
    </h1>


    <div class="clearfix crud-navigation">
        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id],['class' => 'btn btn-info']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . 'Copy', ['create', 'id' => $model->id, 'User            '=>$copyParams],['class' => 'btn btn-success']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'List Users', ['index'], ['class'=>'btn btn-default']) ?>
        </div>

    </div>


    <?php $this->beginBlock('common\models\User'); ?>

    
    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
            'id',
        'username',
        'email:email',
        'auth_key',
        'password_hash',
        'password_reset_token',
        'status',
        'dynamic_key',
        'is_block',
        'is_activated',
        'created_at',
        'updated_at',
    ],
    ]); ?>

    
    <hr/>

    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id],
    [
    'class' => 'btn btn-danger',
    'data-confirm' => '' . 'Are you sure to delete this item?' . '',
    'data-method' => 'post',
    ]); ?>
    <?php $this->endBlock(); ?>


    
    <?= Tabs::widget(
                 [
                     'id' => 'relation-tabs',
                     'encodeLabels' => false,
                     'items' => [ [
    'label'   => '<b class=""># '.$model->id.'</b>',
    'content' => $this->blocks['common\models\User'],
    'active'  => true,
], ]
                 ]
    );
    ?>
</div>
