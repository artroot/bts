<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Telegram */

?>
<div class="telegram-update">

    <?= $this->render('_form', [
        'model' => $model,
        'msg' => @$msg,
        'webHookStatus' => @$webHookStatus
    ]) ?>

</div>
