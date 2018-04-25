<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Taskpriority */

$this->title = 'Update Taskpriority: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Taskpriorities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="taskpriority-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
