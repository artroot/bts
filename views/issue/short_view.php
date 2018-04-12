<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/12/2018
 * Time: 12:29 PM
 * @var $model \app\models\Issue
 */
use yii\helpers\Html;

?>

<div class="thumbnail">
    <p>
        <span class="badge text-uppercase" title="<?= @$model->getPriority()->name ?>" style="background-color: <?= @$model->getPriority()->color ?>;"><?= substr(@$model->getPriority()->name, 0, 1) ?></span>
        <span class="badge text-capitalize"><?= @$model->getType()->name ?></span>
        <span class="badge text-capitalize"><?= @$model->getStatus()->name ?></span>
    </p>
    <p>
        <?php if($model->isDone()): ?>
            <span class="text-muted">
                            <s><?= Html::a($model->index(),['issue/update', 'id' => $model->id], ['class' => 'btn-link']) ?></s> <?= $model->name ?>
                       </span>
        <?php else: ?>
            <span>
                            <?= Html::a($model->index(),['issue/update', 'id' => $model->id], ['class' => 'btn-link']) ?> <?= $model->name ?>
                       </span>
        <?php endif; ?>
        <?= $model->name ?>
    </p>
    <p>
        <?= $model->description ?>
    </p>
</div>
