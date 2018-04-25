<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tasktype */

$this->title = 'Update Tasktype: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Tasktypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tasktype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
