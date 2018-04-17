<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/16/2018
 * Time: 4:50 PM
 * @var $attachments
 * @var $attachment \app\models\Attachment
 */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<ul class="nav nav-pills nav-stacked">
<?php foreach ($attachments as $attachment): ?>
    <li>
        <blockquote style="font-size: small;">
        <?php if (strstr($attachment->type, 'image') !== false): ?>
                <a href="#" data-toggle="modal" data-target="#img_<?= $attachment->id ?>">
                <span class="glyphicon glyphicon-picture"></span>
                <figure style="display: inline;">
                <img class="img-thumbnail" style="height: 70px;" src="<?= Url::to(['attachment/get', 'id' => $attachment->id]) ?>">
                <figcaption style="display: inline;">
                    <?= $attachment->base_name ?>
                </figcaption>
                </figure>
                </a>
                <?php
                Modal::begin([
                    'id' => 'img_' . $attachment->id,
                    'size' => 'modal-lg',
                    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
                ]); ?>
                <figure class="text-center">
                    <img style="max-width: 100%;" src="<?= Url::to(['attachment/get', 'id' => $attachment->id]) ?>">
                </figure>
                <?php Modal::end(); ?>
        <?php else: ?>
                <a data-pjax="0" href="<?= Url::to(['attachment/get', 'id' => $attachment->id]) ?>" download="<?= $attachment->base_name ?>">
                    <span class="glyphicon glyphicon-file"></span> <?= $attachment->base_name ?>
                </a>
        <?php endif; ?>
            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['attachment/delete', 'id' => $attachment->id],
                ['class' => 'pull-right', 'style' => 'position: absolute; right: 0; top: 45%;', 'data' => [
                'confirm' => 'Are you sure you want to delete this attachment?',
                'method' => 'post',
            ]]) ?>
        </blockquote>
    </li>
<?php endforeach; ?>
</ul>