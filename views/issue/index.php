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
    <h3><?= Html::encode($this->title) ?></h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '',
                'content' => function($model){
                    return '<span class="badge text-uppercase" title="'. @$model->getPriority()->name .'" style="background-color: '. @$model->getPriority()->color .';">'. substr(@$model->getPriority()->name, 0, 1) .'</span>';
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
            [
                'label' => 'Owner',
                'content' => function($model){
                    return '<span class="btn btn-xs btn-default"><span class="glyphicon glyphicon-user" title="'. @$model->getOwner()->username .'"></span> '. @$model->getOwner()->index() .'</span>';
                }
            ],
            'create_date',
            'finish_date',
            'deadline',
        ],
    ]); ?>
    
</div>
