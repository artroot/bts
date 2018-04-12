<?php

use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\DetailView;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
/* @var $model app\models\Project */

    // TODO move to project model
    $items = [
        'version' => 'Versions',
        'task' => 'Tasks',
        'team' => 'Team',
        'sprint' => 'Sprint'
    ];

/*$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="project-view shell">

    <h1><?= Html::img(Url::toRoute(
            ['project/get', 'id' => $model->id]),
            ['width' => 48, 'class' => 'img-circle', 'style' => 'display: inline-block; padding-left: 4px;']) ?>
        <?= Html::encode($model->name) ?>
        <span style="float: right;">
			<?= Html::a('', ['project/update', 'id' => $model->id], ['class' => 'btn btn-default glyphicon glyphicon-pencil']) ?>
		</span>
    </h1>

    <div class="row">
        <div class="col-md-3 left-nav hidden-xs">
            <div class="list-group">
                <?php foreach ($items as $controller => $name): ?>
                    <?= Html::a(
                        $name,
                        Url::to([ $controller . '/index', 'project_id' => $model->id]),
                        ['data-pjax' => 'projectItems', 'class' => 'list-group-item project-actions ' . (Yii::$app->controller->id == $controller ? 'active' : '')]
                    ) ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-9 right-container col-xs-12">
            <?php Pjax::begin(['enablePushState' => false, 'id' => 'projectItems', 'linkSelector'=>'a.project-actions']); ?>
                <?= @$main ?>
            <?php Pjax::end(); ?>
        </div>
    </div>

</div>
