<?php

    use app\models\Project;
    use app\models\Issue;
    use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Version */

$this->title = Project::findOne(['id' => $model->project_id])->name . ' ' . $model->name;
/*$this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/

    $allIssue = Issue::find()->where(['version_id' => $model->id])->count();
    $doneIssue = Issue::find()->where(['version_id' => $model->id])->andWhere(['issuestatus_id' => 1])->count();
    $todoIssue = Issue::find()->where(['version_id' => $model->id])->andWhere(['issuestatus_id' => 2])->count();
    $inProgressIssue = Issue::find()->where(['version_id' => $model->id])->andWhere(['issuestatus_id' => 3])->count();

?>
<div class="version-view">

    <h3><?= $model->getStatusIcon() ?> <?= Html::encode($this->title) ?> <span class="label label-warning text-uppercase" style="    font-size: x-small;
    vertical-align: middle;"><?= $model->status ? 'released' : 'unreleased' ?></span></h3>

    <div>
        <div class="progress">
            <div class="progress-bar progress-bar-success" style="width: <?= $allIssue ? $doneIssue*100/$allIssue : 0 ?>%">
            </div>
            <div class="progress-bar progress-bar-warning" style="width: <?= $allIssue ? $inProgressIssue*100/$allIssue : 0 ?>%">
            </div>
            <div class="progress-bar progress-bar-info" style="width: <?= $allIssue ? $todoIssue*100/$allIssue : 0 ?>%">
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1><?= $allIssue ?></h1>
                    <div>Issues in version</div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1 style="color: #5cb85c;"><?= $doneIssue ?></h1>
                    <div>Issues done</div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1 style="color: #f0ad4e;"><?= $inProgressIssue ?></h1>
                    <div>Issues in progress</div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-3">
                <div class="version-dashboard">
                    <h1 style="color: #5bc0de;"><?= $todoIssue ?></h1>
                    <div>Issues todo</div>
                </div>
            </div>
        </div>
    </div>

    <?= $issue ?>

</div>
