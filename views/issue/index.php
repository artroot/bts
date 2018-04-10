<?php

use yii\helpers\Html;
use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\IssueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Issues';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="issue-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a('Create Issue', ['issue/create'], ['class' => 'btn btn-success', 'style' => 'float: right;']) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'label' => '',
                'content' => function($model){
                    return '<span class="badge text-uppercase" title="<?= @$model->getPriority()->name ?>" style="background-color: '. @$model->getPriority()->color .';">'. substr(@$model->getPriority()->name, 0, 1) .'</span>';
                }
            ],
            [
                'label' => '',
                'content' => function($model){
                    return '<span class="badge text-capitalize">'. @$model->getType()->name .'</span>';
                }
            ],
            [
                'label' => 'ID',
                'content' => function($model){
                    $index = $model->isDone() ? sprintf('<s>%s</s>', $model->index()) : $model->index();
                    return  Html::a($index, ['issue/update', 'id' => $model->id], ['class' => $model->isDone() ? 'text-muted' : '']);
                },
                'contentOptions' => ['style' => 'min-width:150px;'],
            ],
            'name',
            //'description:ntext',
            'create_date',
            'finish_date',
            'deadline',
            //'issuetype_id',
            //'issuepriority_id',
            //'issuestatus_id',
            //'sprint_id',
            //'version_id',
            //'resolved_version_id',
            //'detected_version_id',
            //'performer_id',
            //'owner_id',
            //'parentissue_id',
            //'relatedissue_id',


        ],
    ]); ?>
    
</div>
