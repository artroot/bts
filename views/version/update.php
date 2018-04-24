<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

app\assets\AppAsset::register($this);

    Pjax::widget([
        'id' => 'versionFormContainer',  // response goes in this element
        'enablePushState' => false,
        'enableReplaceState' => false,
        'formSelector' => '#versionForm',// this form is submitted on change
        'submitEvent' => 'submit',
    ]);


    /* @var $this yii\web\View */
    /* @var $model app\models\Version */

    $this->title = sprintf('%s %s',\app\models\Project::findOne(['id' => $model->project_id])->name,  $model->name);
    $this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="version-update">

    <?php
        Modal::begin([
            'header' => '<h3>'. $model->getStatusIcon() . ' ' . Html::encode($this->title) .'</h3>',
            'id' => 'versionFormModal',
            'size' => 'modal-lg',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
        ]); ?>

    <div id="versionFormContainer">
        <?= @$versionForm ?>
    </div>

    <?php Modal::end(); ?>

    <script>
        $('#versionFormModal').modal('show');
    </script>


</div>
