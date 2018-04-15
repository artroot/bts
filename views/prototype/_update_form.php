<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Prototype */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="prototype-update">
<div class="prototype-form">

    <?php $form = ActiveForm::begin(['id' => 'prototypeForm', 'action' => $action, 'options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $this->render('file_browser', [
        'model' => $model,
        'form' => $form,
        'path' => explode('/', $model->path)
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
