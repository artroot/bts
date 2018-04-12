<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SprintSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sprints';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprint-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Sprint', ['create'], ['class' => 'btn btn-success sprint-actions']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'ID',
                'content' => function ($model){
                    return Html::a($model->index(), ['sprint/view', 'id' => $model->id]);
                }
            ],
            'name',
            'version_id',
            'start_date',
            'finish_date',

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            ['sprint/update', 'id' => $model->id],
                            ['class' => 'sprint-actions', 'data-pjax' => 'sprints']);
                    },
                ], 'template' => '{update} {delete}'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
