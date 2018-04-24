<?php

use app\models\Project;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

app\assets\AppAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\models\Version */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="version-form">

    <?php $form = ActiveForm::begin(['id' => 'versionForm']); ?>

    <?php if(empty($model->project_id)): ?>

        <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(Project::find()->all(), 'id', 'name')) ?>

    <?php endif; ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'start_date')->textInput() ?>

    <?= $form->field($model, 'plan_date')->textInput() ?>

    <script>
        $(document).ready(function () {
            $(['#version-start_date', '#version-plan_date']).datetimepicker({
                datepicker:true,
                format:'Y-m-d H:i:s'
            });
        });
    </script>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success ']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
