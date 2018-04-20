<?php

    use app\modules\admin\models\State;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Issuestatus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="issuestatus-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state_id')->dropDownList(State::getStates()) ?>

    <?= $form->field($model, 'count_progress_from')->checkbox() ?>
    <?= $form->field($model, 'count_progress_to')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
