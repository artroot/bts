<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;
    use yii\widgets\Pjax;


    Pjax::widget([
        'id' => 'versionFormContainer',  // response goes in this element
        'enablePushState' => false,
        'enableReplaceState' => false,
        'formSelector' => '#versionForm',// this form is submitted on change
        'submitEvent' => 'submit',
    ]);
    
    
/* @var $this yii\web\View */
/* @var $model app\models\Version */

$this->title = 'Create Version';
?>
<div class="version-create">

    <?php
        Modal::begin([
            'header' => '<h1>'. Html::encode($this->title) .'</h1>',
            'id' => 'versionFormModal',
            'size' => 'modal-lg',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
        ]); ?>

    <div id="versionFormContainer">
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
    </div>

    <?php Modal::end(); ?>

    <script>
        $('#versionFormModal').modal('show');
    </script>


</div>
