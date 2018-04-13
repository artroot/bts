<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model app\models\Prototype */

$this->title = 'Create Prototype';
?>
<div class="prototype-create">

    <?php

    Modal::begin([
        'header' => '<h1>'. Html::encode($this->title) .'</h1>',
        'id' => 'prototypeFormModal',
        'size' => 'modal-lg',
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
    ]); ?>

    <div id="prototypeMainForm">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
        </div>
    <?php Modal::end(); ?>

    <script>
        $('#prototypeFormModal').modal('show');
    </script>

</div>
<?php
Pjax::widget([
    'id' => 'prototypeMainForm',  // response goes in this element
    'enablePushState' => false,
    'enableReplaceState' => false,
    'formSelector' => '#prototypeForm',// this form is submitted on change
    'submitEvent' => 'submit',
]);
?>