<?php

/* @var $this yii\web\View */


use app\models\Comment;
use app\models\Issue;
use app\models\IssueSearch;
use app\models\Project;
use app\models\Version;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <!--<h2><center>Dashboard</center></h2>-->

    <div class="row">
        <div class="col-md-4">
            <h3>Project List</h3>
           <?php foreach (Project::find()->orderBy(['id' => SORT_DESC])->all() as $project): ?>
           <a href="<?= Url::to(['project/view', 'id' => $project->id]) ?>">
           <div class="panel panel-primary">
                <div class="panel-heading">
                    <?= Html::img(Url::toRoute(
                        ['project/get', 'id' => $project->id]),
                        ['width' => 24, 'class' => 'img-circle', 'style' => 'display: inline-block; padding-left: 4px;']) ?>
                    <?= $project->name ?>
                </div>
                <div class="panel-body">
                    <?= $project->description ?>
                </div>
            </div>
           </a>
           <?php endforeach; ?>
        </div>
        <div class="col-md-8">
            <?php
            $searchModel = new IssueSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->orderBy(['id' => SORT_DESC]);

            echo $this->render('@app/views/issue/index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
            ?>
        </div>
    </div>
</div>
