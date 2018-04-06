<?php

use app\models\Issue;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RelationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="relation-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'w_associated_with',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Associated with',
                'content' => function($model){
                    $to_issue = Issue::findOne(['id' => $model->to_issue]);
                    return Html::a($to_issue->index() . ' ' . $to_issue->name, ['issue/update', 'id' => $to_issue->id], ['data-pjax'=>0]);
                }
            ],
            'comment',

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-remove"></span>',
                        ['relation/delete', 'id' => $model->id],
                        ['data' => [
                            'confirm' => 'Are you sure you want to delete this relation?',
                            'method' => 'post',
                        ]
                        ]
                    );
                },
            ], 'template' => '{delete}'],
        ],
    ]); ?>
</div>
