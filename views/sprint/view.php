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

$this->title = $model->index() . ' ' . $model->name;
?>
<div class="sprint-view">

    <h3><?= Html::a(@$model->getProject()->name, ['project/view', 'id' => $model->project_id], ['class' => 'ext-muted']) ?></h3>
    <h2><?= Html::encode($this->title) ?></h2>
    <?php if($model->version_id): ?>
       //
    <?php endif; ?>
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
