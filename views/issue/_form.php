<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Issue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="issue-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'finish_date')->textInput() ?>

    <?= $form->field($model, 'plan_date')->textInput() ?>

    <?= $form->field($model, 'issuetype_id')->textInput() ?>

    <?= $form->field($model, 'issuepriority_id')->textInput() ?>

    <?= $form->field($model, 'issuestatus_id')->textInput() ?>

    <?= $form->field($model, 'sprint_id')->textInput() ?>

    <?= $form->field($model, 'version_id')->textInput() ?>

    <?= $form->field($model, 'resolved_version_id')->textInput() ?>

    <?= $form->field($model, 'detected_version_id')->textInput() ?>

    <?= $form->field($model, 'performer_id')->textInput() ?>

    <?= $form->field($model, 'owner_id')->textInput() ?>

    <?= $form->field($model, 'parentissue_id')->textInput() ?>

    <?= $form->field($model, 'relatedissue_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
