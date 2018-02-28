<?php

use yii\helpers\Html;
use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Teams';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-indexshell">
    <h1><?= Html::img(Url::toRoute(
            ['project/get', 'id' => $project->id]),
            ['width' => 48, 'class' => 'img-circle', 'style' => 'display: inline-block; padding-left: 4px;']) ?>
        <?= Html::encode($project->name) ?>
        <span style="float: right;">
			<?= Html::a('', ['project/update', 'id' => $project->id], ['class' => 'btn btn-default glyphicon glyphicon-pencil']) ?>
		</span>
    </h1>

    <div class="row">
        <div class="col-md-3 left-nav hidden-xs">
            <?= \Yii::$app->view->renderFile('@app/views/project/left_nav.php',['project' => $project]) ?>
        </div>
        <div class="col-md-9 right-container col-xs-12">
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <h1><?= Html::encode($this->title) ?> <?= Html::a('Create Team', ['create'], ['class' => 'btn btn-success', 'style' => 'float: right;']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
</div>
</div>
