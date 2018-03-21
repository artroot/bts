<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Issuepriority */

$this->title = 'Update Issuepriority: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Issuepriorities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="issuepriority-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
