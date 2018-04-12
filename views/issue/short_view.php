<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/12/2018
 * Time: 12:29 PM
 * @var $model \app\models\Issue
 */
use yii\helpers\Html;


$description = $model->description;
if (strlen($model->description) > 100) {
    $description = substr($description, 0, 100);
    $description = rtrim($description, "!,.-");
    $description = substr($description, 0, strrpos($description, ' '));
    $description .= '...';
}


?>

<div class="panel panel-info">
    <div class="panel-heading">
        <span class="badge text-uppercase" title="<?= @$model->getPriority()->name ?>" style="background-color: <?= @$model->getPriority()->color ?>;"><?= substr(@$model->getPriority()->name, 0, 1) ?></span>
        <span class="badge text-capitalize"><?= @$model->getType()->name ?></span>
        <?php if($model->isDone()): ?>
            <span class="text-muted">
                            <s><?= Html::a($model->index(),['issue/update', 'id' => $model->id], ['class' => 'btn-link']) ?></s> <?= $model->name ?>
                       </span>
        <?php else: ?>
            <span>
                            <?= Html::a($model->index(),['issue/update', 'id' => $model->id], ['class' => 'btn-link']) ?> <?= $model->name ?>
                       </span>
        <?php endif; ?>
    </div>
    <div class="panel-body">
        <ol class="issue-nav">
            <li title="<?= $model->getOwner()->username ?>"><?= sprintf('Created by %s: %s', $model->getOwner()->first_name . ' ' . $model->getOwner()->last_name ,$model->create_date) ?></li>
        </ol>
        <span class="text-muted"><?= $description ?></span>
    </div>
</div>
