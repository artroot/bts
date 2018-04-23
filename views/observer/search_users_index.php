<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/23/2018
 * Time: 4:33 PM
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use yii\grid\GridView;
use yii\helpers\Html;
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => '',
            'content' => function ($model){
                return Html::checkbox('Observer[observers][]', false, ['value' => $model->id, 'id' => 'select_observer_' . $model->id]);
            }
        ],
        [
            'label' => 'Index',
            'content' => function ($model){
                return sprintf('<label class="btn btn-link" for="select_observer_%s">%s (%s)</label>', $model->id , @$model->index(), $model->username);
            }
        ]
    ],
]);

?>
