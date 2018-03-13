<?php

    use app\models\Usertype;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(['id' => 'userForm', 'action' =>['/admin/users/update?id=' . $model->id]]); ?>
    <h4 id="mainSettings">Main</h4>


    <?= $form->field($model, 'usertype_id')->dropDownList(ArrayHelper::map(Usertype::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'conf_password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

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
