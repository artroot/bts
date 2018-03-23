<?php

    use app\models\Issuestatus;
    use app\models\Project;
    use app\models\Issue;
    use app\models\State;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\DetailView;
    use yii\widgets\Pjax;

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
            <div class="progress-bar <?= $state['state']->class ?>" style="width: <?= $allIssue ? $state['count']*100/$allIssue : 0 ?>%"></div>
            <?php endforeach; ?>
        </div>
        <div class="row issue-tab">
            <div class="col-md-2 col-sm-2 col-xs-3 active">
                <a class="version-dashboard" data-pjax="issueList" href="<?= Url::to('/issue/index') ?>">
                    <h1><?= $allIssue ?></h1>
                    <div>Issues in version</div>
                </a>
            </div>
            <?php foreach ($states as $state): ?>
                <?php if ($state['count'] > 0): ?>
                <div class="col-md-2 col-sm-2 col-xs-3 <?= $state['state']->class ?>">
                    <a class="version-dashboard" data-pjax="issueList" href="<?= Url::to(['/issue/index', 'state' => $state['state']->id]) ?>">
                        <h1><?= $state['count'] ?></h1>
                        <div>Issues <?= $state['state']->label ?></div>
                    </a>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <?php Pjax::begin(['id' => 'issueList', 'enablePushState' => false, 'linkSelector' => 'a.version-dashboard']); ?>
    <?= $issue ?>
    <?php Pjax::end(); ?>

</div>

<script>
    $('.issue-tab > *').click(function () {
        $('.issue-tab > *').removeClass('active');
        $(this).addClass('active');
    });
</script>
