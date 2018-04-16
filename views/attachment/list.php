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
use yii\helpers\Url;

?>

<ul class="nav nav-pills nav-stacked">
<?php foreach ($attachments as $attachment): ?>
    <li>
        <blockquote style="font-size: small;">
        <?php if (strstr($attachment->type, 'image') !== false): ?>
                <a data-toggle="modal" data-target="#img_<?= $attachment->id ?>">
                <figure>
                <img height="70px" src="<?= Url::to(['attachment/get', 'id' => $attachment->id]) ?>">
                <figcaption>
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
        </blockquote>
    </li>
<?php endforeach; ?>
</ul>