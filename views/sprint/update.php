<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Sprint */

$this->title = 'Update ' . $model->index();
?>
<div class="sprint-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div id="sprintMainForm">
        <?= $this->render('_form', [
            'model' => $model,
            'action' => $action
        ]) ?>
    </div>
</div>
<?php
Pjax::widget([
    'id' => 'sprintMainForm',  // response goes in this element
    'enablePushState' => false,
    'enableReplaceState' => false,
    'formSelector' => '#sprintForm',// this form is submitted on change
    'submitEvent' => 'change',
]);
?>
