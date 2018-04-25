<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'finish_date')->textInput() ?>

    <?= $form->field($model, 'plan_date')->textInput() ?>

    <?= $form->field($model, 'tasktype_id')->textInput() ?>

    <?= $form->field($model, 'taskpriority_id')->textInput() ?>

    <?= $form->field($model, 'taskstatus_id')->textInput() ?>

    <?= $form->field($model, 'sprint_id')->textInput() ?>

    <?= $form->field($model, 'version_id')->textInput() ?>

    <?= $form->field($model, 'resolved_version_id')->textInput() ?>

    <?= $form->field($model, 'detected_version_id')->textInput() ?>

    <?= $form->field($model, 'performer_id')->textInput() ?>

    <?= $form->field($model, 'owner_id')->textInput() ?>

    <?= $form->field($model, 'parenttask_id')->textInput() ?>

    <?= $form->field($model, 'relatedtask_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
