<?php

use app\models\Users;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\Log */
/* @var $modelClass \yii\db\ActiveRecord */
/* @var $searchModel app\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs';
?>
<div class="log-index">
    <?php foreach ($logModels as $model): ?>
        
        <p style="border-bottom: 1px solid #ccc;">
            <span><?= Users::findOne(['id' => $model->user_id])->index() ?></span>
            <span class="pull-right"><?= $model->date ?></span>
        </p>
            <?php
            $data_new = unserialize($model->data_new);
            $modelClass = $model->model;
            $modelClass = new $modelClass();
            ?>
            <?php foreach (unserialize($model->data_old) as $key => $value): ?>
                <div class="row">
                    <div class="col-xs-4">
                        <?= $modelClass->attributeLabels()[$key] ?>
                    </div>
                    <div class="col-xs-8">
                        <p><?= $value ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= @$data_new[$key] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

    <?php endforeach; ?>
</div>
