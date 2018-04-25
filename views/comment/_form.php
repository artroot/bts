<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>



        <?php $form = ActiveForm::begin(['action' => $action]); ?>

        <?= $form->field($model, 'issue_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'text')->textarea(['rows' => 6])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton($model->id ? 'Save changes' : 'Send comment', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

