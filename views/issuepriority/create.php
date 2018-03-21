<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Issuepriority */

$this->title = 'Create Issuepriority';
$this->params['breadcrumbs'][] = ['label' => 'Issuepriorities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issuepriority-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
