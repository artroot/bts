<?php

use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\DetailView;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <h1><?= Html::img(Url::toRoute(
            ['project/get', 'id' => $model->id]),
            ['width' => 48, 'class' => 'img-circle', 'style' => 'display: inline-block; padding-left: 4px;']) ?> <?= Html::encode($this->title) ?></h1>

    <div class="row">
        <?php Pjax::begin(); ?>
        <div class="col-md-3">
        </div>

        <div class="col-md-9"><?= $data ?></div>
        <?php Pjax::end(); ?>
    </div>
</div>
