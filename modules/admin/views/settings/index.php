<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */

    $this->title = 'Settings';
?>
<div class="settings-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
    <?php Pjax::begin(['id' => 'settings','enablePushState' => false, 'linkSelector' => 'a.list-group-item']); ?>
        <div class="col-md-3">
            <div class="list-group">
                <?= Html::a('Main', ['settings/main'], ['class' => sprintf('list-group-item %s', $active == 'main' ? 'active' : ''), 'data-pjax'=>'settings']) ?>
                <?= Html::a('Statuses', ['settings/statuses'], ['class' => sprintf('list-group-item %s', $active == 'statuses' ? 'active' : ''), 'data-pjax'=>'settings']) ?>
                <?= Html::a('Notification', ['settings/notification'], ['class' => sprintf('list-group-item %s', $active == 'notification' ? 'active' : ''), 'data-pjax'=>'settings']) ?>
                <?= Html::a('Users', ['settings/users'], ['class' => sprintf('list-group-item %s', $active == 'users' ? 'active' : ''), 'data-pjax'=>'settings']) ?>
            </div>
        </div>
        <div class="col-md-9" id="settings">
            <?= @$data ?>
        </div>
    <?php Pjax::end(); ?>
    </div>
</div>
