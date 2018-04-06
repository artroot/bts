<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/6/2018
 * Time: 4:11 PM
 */
use app\models\Project;
use yii\grid\GridView;
use yii\helpers\Html;

?>

<?= /** @var  $dataProvider */
/** @var  $searchModel */



GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => '',
            'content' => function ($model){
                return Html::checkbox('Relation[to_issues][]', false, ['value' => $model->id, 'id' => 'select_issue_' . $model->id]);
            }
        ],
        [
            'label' => 'Index',
            'content' => function ($model){
                return sprintf('<label class="btn btn-link" for="select_issue_%s">%s %s</label>', $model->id , $model->index(), $model->name);
            }
        ]
    ],
]); ?>
