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

    <h3><?= Html::encode($this->title) ?></h3>
    <?php Pjax::begin(); ?>
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
            [
                'label' => 'Version',
                'content' => function ($model){
                    return @$model->getVersion()->name;
                }
            ],
            'start_date',
            'finish_date',
            [
                'label' => 'Progress',
                'content' => function ($model){
                    return '
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'. $model->getCompleteProgressPercent() .'" aria-valuemin="0" aria-valuemax="100" style="min-width: 30px; width: '. $model->getCompleteProgressPercent() .'%">
                                <span>'. round($model->getCompleteProgressPercent()) .'% Complete</span>
                            </div>
                        </div>
                    ';
                }
            ],

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
