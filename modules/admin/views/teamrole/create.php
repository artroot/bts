<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Teamrole */

$this->title = 'Create Teamrole';
$this->params['breadcrumbs'][] = ['label' => 'Teamroles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teamrole-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
