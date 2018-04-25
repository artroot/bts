<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\NotifyruleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="notifyrule-index">
    <?php foreach ($models as $model): ?>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
        <?php
        Pjax::widget([
            'id' => 'notifyruleContainer' . $model->id,
            'enablePushState' => false,
            'enableReplaceState' => false,
            'formSelector' => '#notifyruleForm' . $model->id,
            'submitEvent' => 'change',
        ]);
        ?>
    <?php endforeach; ?>
</div>
