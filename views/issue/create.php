<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Issue */

$this->title = 'Create Issue';
/*$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="task-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
