<?php

use yii\helpers\Html;
use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Teams';
?>
<div class="team-index">

    <h3><?= Html::encode($this->title) ?> <?= Html::a('Create Team', ['create'], ['class' => 'btn btn-success', 'style' => 'float: right;']) ?></h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
</div>
