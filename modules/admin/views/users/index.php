<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(
            'Create Users',
            ['/settings/create'],
            ['data-pjax' => 'userSettings', 'class' => 'user-settings btn btn-success']
        ) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'State',
                'content' => function($model){
                    return $model->status == User::STATUS_ACTIVE ? 'active' : 'deleted';
                }
            ],
            [
                'label' => 'Group',
                'content' => function($model){
                    return @$model->getUsertype()->one()->name;
                }
            ],
            'username',
            'first_name',
            'last_name',

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            ['/settings/index', 'id' => $model->id],
                            ['data-pjax' => 'userSettings', 'class' => 'user-settings']
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-off" style="color: ' . ($model->status == User::STATUS_ACTIVE ? 'red' : 'green') . ';"></span>',
                            ['/admin/users/delete', 'id' => $model->id],
                            ['data' => [
                                    'confirm' => 'Are you sure you want to delete this user?',
                                    'method' => 'post',
                                ]
                            ]
                        );
                    },
                ], 'template' => '{update} {delete}'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
