<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Prototype */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="prototype-create">
<div class="prototype-form">

    <?php $form = ActiveForm::begin(['id' => 'prototypeForm', 'options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'resource')->fileInput(['accept' => 'application/zip']) ?>

    <?= $form->field($model, 'issue_id')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
