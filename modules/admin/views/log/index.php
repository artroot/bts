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

?>
<div class="log-index">
    <?php foreach ($logModels as $model): ?>
        <?php
        $data_old = unserialize($model->data_old);
        $data_new = unserialize($model->data_new);
        $modelClass = $model->model;
        $modelClass = new $modelClass();
        $modelClassName = explode('\\', $model->model);
        $modelClassName = array_pop($modelClassName);
        unset($data_new['issue_id'], $data_new['id']);
        ?>
        <p style="border-bottom: 1px solid #ccc;">
            <span><?= Users::findOne(['id' => $model->user_id])->index() ?></span>
             <span><?= $model->action ?> <?= $modelClassName ?></span>
            <span class="pull-right"><?= $model->date ?></span>
        </p>
            <?php foreach ($data_new as $key => $value): ?>
                <div class="row">
                    <div class="col-xs-4">
                        <b><?= $modelClass->attributeLabels()[$key] ?></b>
                    </div>
                    <div class="col-xs-8">
                        <p>
                        <?php if($key == 'description'): ?>
                        <p><mark><?= nl2br(@$data_old[$key]) ?></mark></p>
                        <p><?= nl2br($value) ?></p>
                        <?php else: ?>
                        <?= @$data_old[$key] ?> <span class="glyphicon glyphicon-arrow-right"></span> <?= $value ?>
                        <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>

    <?php endforeach; ?>
</div>
