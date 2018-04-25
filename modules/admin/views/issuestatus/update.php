<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Issuestatus */

$this->title = 'Update Issuestatus: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['settings/statuses']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issuestatus-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
