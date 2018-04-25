<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */

?>
<div class="comment-update">

    <?= $this->render('_form', [
        'model' => $model,
        'action' => ['/comment/update', 'id' => $model->id]
    ]) ?>

</div>
