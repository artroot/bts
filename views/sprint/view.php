<?php

use app\components\SVG;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Sprint */
/* @var array $sprintIssues */

$svg = SVG::generate($model->getProgress(), $model->getSprintCountDays());

$this->title = @$model->getProject()->name . ' ' . $model->index();


$this->params['titleItems'] = [
    'label' => $this->title,
    'items' => [
        [
            'label' => '<center>' . $model->name . '</center>'
        ],
        [
            'label' => '<center><span class="text-muted">
        <span class="glyphicon glyphicon-hourglass"></span><span>'. $model->getDaysRemaining() .' days remaining</span>
    </span></center>'
        ],
        [
            'label' => '<li class="divider"></li>'
        ],
        [
            'label' => '<li>' . Html::a('Edit sprint', ['sprint/update', 'id' => $model->id], ['data-pjax' => 'sprints', 'class' => 'sprint-actions']) . '</li>',
        ],
        [
            'label' => '<li>' . Html::a('Delete sprint', ['sprint/delete', 'id' => $model->id], ['data' => [
                    'confirm' => 'Are you sure you want to delete this sprint?',
                    'method' => 'post',
                ]]) . '</li>',

        ],
    ]
];


?>
<div class="sprint-view">

    <h3><?= Html::a(@$model->getProject()->name, ['project/view', 'id' => $model->project_id], ['class' => 'ext-muted']) ?>
    <?= $model->index() ?>
    <span style="float: right;">
        Version: <?= $model->version_id ? Html::a($model->getVersion()->name, ['version/view', 'id' => $model->version_id]) : '<span class="not-set">(not set)</span>' ?>
    </span>
    </h3>
    <h3><?= $model->name ?></h3>
    <p><?= sprintf('<span class="btn-link">%s</span> - <span class="btn-link">%s</span>', $model->start_date, $model->finish_date) ?>
        <span class="text-muted">
        <span class="glyphicon glyphicon-hourglass"></span><span><?= $model->getDaysRemaining() ?> days remaining</span>
    </span>
    </p>

    <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?= $model->getCompleteProgressPercent() ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $model->getCompleteProgressPercent() ?>%">
            <span><?= round($model->getCompleteProgressPercent()) ?>% Complete</span>
        </div>
    </div>

    <?php if ($model->getIssues()->count() > 0): ?>
    <div>
        <h3><center>BurnDown Diagram</center></h3>
        <?= $this->render('graph', [
            'graphs' => [
                $svg->getIdeal(),
                $svg->getCoords(),
            ],
            'scales' => $svg->getScales(),
        ])
        ?>
        
    </div>
    <br>
    <br>
    <?php endif; ?>
    <h3><center>Issues Desk</center></h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
            <?php foreach ($sprintIssues as $status => $issues): ?>
                <th><?= $status ?></th>
            <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
        <tr>
        <?php foreach ($sprintIssues as $status => $issues): ?>
                <td>
                    <?php foreach ($issues as $issue): ?>
                        <?= $this->render('@app/views/issue/short_view', ['model' => $issue]) ?>
                    <?php endforeach; ?>
                </td>
        <?php endforeach; ?>
        </tr>
        </tbody>
    </table>
        
</div>
