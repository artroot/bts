<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VersionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="version-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-horizontal',
            'data-pjax' => 1
        ],
        'fieldConfig' => [
            'template' => "<div class=\"col-sm-3\">{label}</div>\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-12 col-sm-offset-3\">{error}</div>",
            'labelOptions' => ['class' => ''],
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'start_date') ?>

    <?= $form->field($model, 'finish_date') ?>

    <?php // echo $form->field($model, 'finish_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
