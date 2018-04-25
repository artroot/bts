<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Taskviewer */

$this->title = 'Update Taskviewer: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Taskviewers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="taskviewer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
