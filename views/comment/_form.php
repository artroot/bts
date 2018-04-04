<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default">

    <div class="panel-heading">
        <span class="glyphicon glyphicon-comment"></span>
        <span><?= Yii::$app->user->identity->username ?></span>
    </div>

    <div class="comment-form panel-body">

        <?php $form = ActiveForm::begin(['action' => '/comment/create']); ?>

        <?= $form->field($model, 'issue_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'text')->textarea(['rows' => 6])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>