<?php

    use app\modules\admin\models\Telegram;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Telegram */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="telegram-form">
    <br>
    <?php if (@$msg): ?>
        <div class="alert alert-success" role="alert"><?= $msg ?></div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(['action' =>['telegram/update?id=' . $model->token], 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'status')->checkbox([
        'value' => 1,
        'checked' => $model->status,
        'onchange' => '$(\'.telegram-form-inputs\').toggle(\'fast\');'
    ]) ?>

    <div class="telegram-form-inputs" style="display: <?= !$model->status ? 'none' : 'block' ?>">

        <label>WebHook status <br>
            <?= @$webHookStatus ?>
        </label>
        <br>
        <br>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'base_url')->textInput(['maxlength' => true, '']) ?>

        <div class="alert alert-info" role="alert">
        <i>You must attach your SSL certificate if it is self-signed or it can not be verify.</i>
        <?= $form->field($model, 'certificate')->fileInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
