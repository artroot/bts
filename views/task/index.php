<?php

use yii\helpers\Html;
use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success', 'style' => 'float: right;']) ?></h1>
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
            //'tasktype_id',
            //'taskpriority_id',
            //'taskstatus_id',
            //'sprint_id',
            //'version_id',
            //'resolved_version_id',
            //'detected_version_id',
            //'performer_id',
            //'owner_id',
            //'parenttask_id',
            //'relatedtask_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
</div>
