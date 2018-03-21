<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Issuetype */

$this->title = 'Update Issuetype: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Issuetypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="issuetype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
