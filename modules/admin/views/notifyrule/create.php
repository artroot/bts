<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Notifyrule */

$this->title = 'Create Notifyrule';
$this->params['breadcrumbs'][] = ['label' => 'Notifyrules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifyrule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
