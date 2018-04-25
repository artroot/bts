<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tasktype */

$this->title = 'Create Tasktype';
$this->params['breadcrumbs'][] = ['label' => 'Tasktypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasktype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
