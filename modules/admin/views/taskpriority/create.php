<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Taskpriority */

$this->title = 'Create Taskpriority';
$this->params['breadcrumbs'][] = ['label' => 'Taskpriorities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="taskpriority-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
