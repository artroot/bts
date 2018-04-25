<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Comment */

?>
<div class="comment-create">
    <div class="panel panel-default">

    <div class="panel-heading">
        <span class="glyphicon glyphicon-comment"></span>
        <span><?= Yii::$app->user->identity->index() ?></span>
    </div>

    <div class="comment-form panel-body">
    <?= $this->render('_form', [
        'model' => $model,
        'action' => '/comment/create'
    ]) ?>
  </div>

</div>
</div>
