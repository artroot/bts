<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sprint */

$this->title = 'Update Sprint: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Sprints', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sprint-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => @$action
    ]) ?>

</div>
