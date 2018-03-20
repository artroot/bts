<?php

    use app\models\Project;
    use app\models\Task;
    use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Version */

$this->title = Project::findOne(['id' => $model->project_id])->name . ' ' . $model->name;
/*$this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/

    $allTask = Task::find()->where(['version_id' => $model->id])->count();
    $doneTask = Task::find()->where(['version_id' => $model->id])->andWhere(['taskstatus_id' => 1])->count();
    $todoTask = Task::find()->where(['version_id' => $model->id])->andWhere(['taskstatus_id' => 2])->count();
    $inProgressTask = Task::find()->where(['version_id' => $model->id])->andWhere(['taskstatus_id' => 3])->count();

?>
<div class="version-view">

    <h3><?= $model->getStatusIcon() ?> <?= Html::encode($this->title) ?> <span class="label label-warning text-uppercase" style="    font-size: x-small;
    vertical-align: middle;"><?= $model->status ? 'released' : 'unreleased' ?></span></h3>

    <div>
        <div class="progress">
            <div class="progress-bar progress-bar-success" style="width: <?= $allTask ? $doneTask*100/$allTask : 0 ?>%">
                <!--<span class="sr-only">35% Complete (success)</span>-->
            </div>
            <div class="progress-bar progress-bar-warning" style="width: <?= $allTask ? $inProgressTask*100/$allTask : 0 ?>%">
                <!--<span class="sr-only">20% Complete (warning)</span>-->
            </div>
            <div class="progress-bar progress-bar-info" style="width: <?= $allTask ? $todoTask*100/$allTask : 0 ?>%">
                <!--<span class="sr-only">10% Complete (danger)</span>-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1><?= $allTask ?></h1>
                    <div>Issues in version</div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1 style="color: #5cb85c;"><?= $doneTask ?></h1>
                    <div>Issues done</div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1 style="color: #f0ad4e;"><?= $inProgressTask ?></h1>
                    <div>Issues in progress</div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1 style="color: #5bc0de;"><?= $todoTask ?></h1>
                    <div>Issues todo</div>
                </div>
            </div>
        </div>
    </div>

    <?= $task ?>

</div>
