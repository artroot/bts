<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <svg id="koalaLogo" data-name="koalaLogo" style="height: 100px;" viewBox="0 0 362.81 265.92"><defs><style>.cls-1{fill:#99aab4;}.cls-2{fill:#677580;}.cls-3{fill:#2a2f33;}</style></defs><title>Koala Bug Tracking System</title><ellipse class="cls-1" cx="181.31" cy="240.92" rx="46.5" ry="25"/><path class="cls-1" d="M134,38c-8.11-4.94-28.25-15.65-55-14-11.17.69-33.31,2.06-49,19C10.56,64,15.2,93.55,17,105c1.53,9.72,4.85,30.86,23,46a63.15,63.15,0,0,0,27,13" transform="translate(-15.19 -14.08)"/><path class="cls-1" d="M259.19,38c8.11-4.94,28.25-15.65,55-14,11.17.69,33.31,2.06,49,19,19.44,21,14.8,50.55,13,62-1.53,9.72-4.85,30.86-23,46a63.15,63.15,0,0,1-27,13" transform="translate(-15.19 -14.08)"/><path class="cls-2" d="M111,53c-5.57-.93-24.32-3.34-42,8-4.39,2.82-14.81,9.71-20,23-4.38,11.22-2.7,21-1,31a84.15,84.15,0,0,0,19,40" transform="translate(-15.19 -14.08)"/><path class="cls-2" d="M282.22,53c5.57-.93,24.32-3.34,42,8,4.39,2.82,14.81,9.71,20,23,4.38,11.22,2.7,21,1,31a84.15,84.15,0,0,1-19,40" transform="translate(-15.19 -14.08)"/><path class="cls-1" d="M300.34,76c-7.14-10.09-16.51-23.08-31.8-34.82A124.49,124.49,0,0,0,251.12,30C230,19,204,8,184,18c-3,2,1,15,2.27,23.25.63,3.07,1.34,5.72,1.79,7.87C180,78,174,109,190.45,136.78c.77,2.22,1.33,4,1.49,9L195.49,265c6,.51,90.06,6.68,122-42,9.83-15,11.4-30.16,12.84-44C335.67,127.17,308.93,88.15,300.34,76Z" transform="translate(-15.19 -14.08)"/><path class="cls-1" d="M204.32,15c-16.32-3-31.32,3-46,7.5-4.32,1.82-10,4.33-15.89,7.5-26,13.94-39.33,32.54-48.75,46-8.51,12.15-35,51.17-29.68,103,1.42,13.84,3,29,12.72,44,31.61,48.68,114.9,42.51,120.82,42l3.53-119.27c.15-5,.81-6.84,1.69-9.11C221,110,215,78,204.52,49.36c.18-2,.57-4.44.92-7.48C206,33,207,25,207.27,15.57A7.48,7.48,0,0,1,204.32,15Z" transform="translate(-15.19 -14.08)"/><path class="cls-3" d="M196,141.57c-5,.43-9,1.43-13.17,4.32a47.38,47.38,0,0,0-7.27,5.47A42.33,42.33,0,0,0,169,159c-3.72,5.57-5.19,10.68-7,17a89.38,89.38,0,0,0-3,32c.83,9.8,1.27,15,5,21,5.51,8.82,14,12.61,18,14,8,2.8,14.67,1.87,21,1,6.94-1,12.58-1.74,18-6,5.91-4.65,8.1-10.73,10-16,2.84-7.89,2.95-14.36,3-21a86.22,86.22,0,0,0-4-27c-2.35-7.18-4.46-13.62-10-20a42.38,42.38,0,0,0-12.93-10C202.77,141.84,198.87,141.91,196,141.57Z" transform="translate(-15.19 -14.08)"/><circle class="cls-3" cx="123.31" cy="137.42" r="15.5"/><circle class="cls-3" cx="240.31" cy="137.42" r="15.5"/></svg>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
