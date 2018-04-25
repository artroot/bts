<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\NotifyruleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notifyrule-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'chapter') ?>

    <?= $form->field($model, 'mail') ?>

    <?= $form->field($model, 'telegram') ?>

    <?php // echo $form->field($model, 'owner') ?>

    <?php // echo $form->field($model, 'performer') ?>

    <?php // echo $form->field($model, 'create') ?>

    <?php // echo $form->field($model, 'update') ?>

    <?php // echo $form->field($model, 'delete') ?>

    <?php // echo $form->field($model, 'done') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
