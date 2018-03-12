<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */

    $this->title = 'Settings';
?>
<div class="settings-index shell">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
    <?php Pjax::begin(['id' => 'settings','enablePushState' => false]); ?>
        <div class="col-md-3 left-nav hidden-xs">
            <div class="list-group">
                <?= Html::a('Main', ['settings/main'], ['class' => sprintf('list-group-item %s', $active == 'main' ? 'active' : ''), 'data-pjax'=>1]) ?>
                <?= Html::a('Notification', ['settings/notification'], ['class' => sprintf('list-group-item %s', $active == 'notification' ? 'active' : ''), 'data-pjax'=>1]) ?>
                <?= Html::a('Users', ['settings/users'], ['class' => sprintf('list-group-item %s', $active == 'users' ? 'active' : ''), 'data-pjax'=>1]) ?>
            </div>
        </div>
        <div class="col-md-9 right-container col-xs-12">
            <?= @$data ?>
        </div>
    <?php Pjax::end(); ?>
    </div>
</div>
