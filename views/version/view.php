<?php

    use app\models\Issuestatus;
    use app\models\Project;
    use app\models\Issue;
    use app\models\State;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Version */

$this->title = Project::findOne(['id' => $model->project_id])->name . ' ' . $model->name;
/*$this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/

    $states = [
        0 => [
            'count' => Issue::getDone(['version_id' => $model->id])->count(),
            'state' => State::getState(State::DONE)
        ],
        1 => [
            'count' => Issue::getTodo(['version_id' => $model->id])->count(),
            'state' => State::getState(State::TODO)
        ],
        2 => [
            'count' => Issue::getInProgress(['version_id' => $model->id])->count(),
            'state' => State::getState(State::IN_PROGRESS)
        ],
    ];

    $allIssue = Issue::find()->where(['version_id' => $model->id])->count();
    //$doneIssue = Issue::getDone(['version_id' => $model->id])->count();
    //$todoIssue = Issue::getTodo(['version_id' => $model->id])->count();
    //$inProgressIssue = Issue::getInProgress(['version_id' => $model->id])->count();
?>
<div class="version-view">

    <h3><?= $model->getStatusIcon() ?> <?= Html::encode($this->title) ?> <span class="label label-warning text-uppercase" style="    font-size: x-small;
    vertical-align: middle;"><?= $model->status ? 'released' : 'unreleased' ?></span></h3>

    <div>
        <div class="progress">
            <?php foreach ($states as $state): ?>
            <div class="progress-bar" style="background-color: <?= $state['state']->color ?>; width: <?= $allIssue ? $state['count']*100/$allIssue : 0 ?>%"></div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1><?= $allIssue ?></h1>
                    <div>Issues in version</div>
                </div>
            </div>
            <?php foreach ($states as $state): ?>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <div class="version-dashboard">
                        <h1 style="color: <?= $state['state']->color ?>;"><?= $state['count'] ?></h1>
                        <div>Issues <?= $state['state']->label ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?= $issue ?>

</div>
