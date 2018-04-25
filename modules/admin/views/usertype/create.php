<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Usertype */

$this->title = 'Create Usertype';
$this->params['breadcrumbs'][] = ['label' => 'Usertypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usertype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
