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
        'issue' => 'Issues',
        'team' => 'Team',
        'sprint' => 'Sprint'
    ];


$this->title = $model->name;


$this->params['titleItems'] = [
    'label' => $this->title,
    'items' => [
        [
            'label' => '<center>' . Html::img(Url::toRoute(
                ['project/get', 'id' => $model->id]),
                ['width' => 48, 'class' => 'img-circle', 'style' => 'display: inline-block; padding-left: 4px;']) . '</center>'
        ],
        [
          'label' => '<center>' . 'Project ' . $this->title . '</center>'
        ],
        [
            'label' => '<li class="divider"></li>'
        ],
        [
            'label' => '<li>' . Html::a('Create version', ['version/create', 'project_id' => $model->id], ['data-pjax' => 'versions', 'class' => 'version-actions']) . '</li>',
        ],
        [
            'label' => '<li>' . Html::a('Create issue', ['issue/create', 'project_id' => $model->id]) . '</li>',
        ],
        [
            'label' => '<li>' . Html::a('Create sprint', ['sprint/create', 'project_id' => $model->id], ['data-pjax' => 'sprints', 'class' => 'sprint-actions']) . '</li>',
        ],
        [
          'label' => '<li class="divider"></li>'
        ],
        [
            'label' => 'Edit project', 'url' => ['project/update', 'id' => $model->id]
        ],
        [
            'label' => '<li>' . Html::a('Delete project', ['project/delete', 'id' => $model->id], ['data' => [
                    'confirm' => 'Are you sure you want to delete this project?',
                    'method' => 'post',
                ]]) . '</li>',

        ],
    ]
];

?>
<div class="project-view">
    <br>
    <div class="row">
        <div class="col-md-3">
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
        <div class="col-md-9 col-xs-12">
            <?php Pjax::begin(['enablePushState' => false, 'id' => 'projectItems', 'linkSelector'=>'a.project-actions']); ?>
                <?= @$main ?>
            <?php Pjax::end(); ?>
        </div>
    </div>

</div>
