<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\IssuestatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Issuestatuses';
?>
<div class="issuestatus-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Issuestatus', ['issuestatus/create'], ['class' => 'btn btn-success', 'data-pjax' => 'settings']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'state_id',

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-edit"></span>',
                            ['issuestatus/update', 'id' => $model->id],
                            ['data-pjax' => 'settings']
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-remove"></span>',
                            ['issuestatus/delete', 'id' => $model->id],
                            ['data' => [
                                'confirm' => 'Are you sure you want to delete this status?',
                                'method' => 'post',
                            ],
                                'data-pjax' => 'settings'
                            ]
                        );
                    },
                ], 'template' => '{update} {delete}'],
        ],
    ]); ?>
</div>
