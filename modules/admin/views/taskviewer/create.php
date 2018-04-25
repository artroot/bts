<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Taskviewer */

$this->title = 'Create Taskviewer';
$this->params['breadcrumbs'][] = ['label' => 'Taskviewers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="taskviewer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
