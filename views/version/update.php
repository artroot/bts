<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Version */

$this->title = 'Update Version: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="version-update">

    <?php
        Modal::begin([
            'header' => '<h1>'. Html::encode($this->title) .'</h1>',
            'id' => 'versionFormModal',
            'size' => 'modal-lg',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
        ]); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php Modal::end(); ?>

    <script>
        $('#versionFormModal').modal('show');
    </script>

</div>
