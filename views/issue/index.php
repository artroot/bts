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

    <h1><?= Html::encode($this->title) ?> <?= Html::a('Create Issue', ['create'], ['class' => 'btn btn-success', 'style' => 'float: right;']) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',
            'create_date',
            'finish_date',
            //'plan_date',
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

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            ['issue/update', 'id' => $model->id]
                        );
                    },
                ], 'template' => '{update} {delete}'],
        ],
    ]); ?>
    
</div>
