<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Sprint */

$this->title = 'Update ' . $model->index();
?>
<div class="sprint-update">

    <?php
    Modal::begin([
        'header' => '<h1>'. Html::encode($this->title) .'</h1>',
        'id' => 'sprintFormModal',
        'size' => 'modal-lg',
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
    ]); ?>

    <div id="sprintMainForm">
        <?= $this->render('_form', [
            'model' => $model,
            'action' => $action,
            'disableProjectAndVersion' => $disableProjectAndVersion
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::button('Save', ['class' => 'btn btn-success', 'onclick' => '$(\'#sprintForm\').attr(\'action\', \'/sprint/update?id=' . $model->id . '\').submit();']) ?>
    </div>

    <?php Modal::end(); ?>

    <script>
        $('#sprintFormModal').modal('show');
    </script>
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
