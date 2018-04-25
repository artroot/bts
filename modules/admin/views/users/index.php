<?php

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
            'usertype_id',
            'username',
            'password',
            'password_hash:ntext',
            //'password_reset_token:ntext',
            //'email:email',
            //'auth_key:ntext',
            //'status',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            ['/settings/index', 'id' => $model->id],
                            ['data-pjax' => 'userSettings', 'class' => 'user-settings']
                        );
                    },
                ], 'template' => '{update} {delete}'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
