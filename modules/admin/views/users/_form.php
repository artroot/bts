<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(['id' => 'userForm', 'action' =>['/admin/users/update?id=' . $model->id]]); ?>
    <h4 id="mainSettings">Main</h4>

    <?= $form->field($model, 'usertype_id')->textInput() ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <h4 id="notifySettings">Notifications</h4>

    <?= $form->field($model, 'telegram_key')->textInput() ?>


    <?php if (@$msg): ?>
        <div class="alert alert-success" role="alert"><?= $msg ?></div>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
