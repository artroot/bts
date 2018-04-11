<?php

use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
/* @var $model app\models\Issue */

$this->title = 'Update Issue';
?>
<div class="issue-update" id="issueUpForm">

    <?= $this->render('_update_form', [
        'model' => $model,
        'action' => $action
    ]) ?>

</div>
<?php
    Pjax::widget([
        'id' => 'issueUpForm',  // response goes in this element
        'enablePushState' => false,
        'enableReplaceState' => false,
        'formSelector' => '#issueForm',// this form is submitted on change
        'submitEvent' => 'change',
    ]);
/*
    Pjax::widget([
        'id' => 'issueUpForm',  // response goes in this element
        'enablePushState' => false,
        'enableReplaceState' => false,
        'formSelector' => '#issueForm',// this form is submitted on change
        'submitEvent' => 'submit',
    ]);*/

?>
