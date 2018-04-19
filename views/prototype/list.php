<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/16/2018
 * Time: 1:48 PM
 * @var $prototypeList \yii\db\ActiveRecord
 * @var $model \app\models\Issue
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>
<ul class="nav nav-pills nav-stacked">
<?php foreach ($prototypeList as $prototype): ?>
    <li>
    <blockquote class="" style="font-size: small;">
        <a href="<?= Url::to(['prototype/view', 'id' => $prototype->id]) ?>" target="_blank">
        <span class="glyphicon glyphicon-compressed"></span>
        <?= $prototype->index() ?> <?= $prototype->name ?>
        </a>
        <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['prototype/delete', 'id' => $prototype->id],
            ['class' => 'pull-right', 'style' => 'position: absolute; right: 0; top: 45%;', 'data' => [
                'confirm' => 'Are you sure you want to delete this prototype?',
                'method' => 'post',
            ]]) ?>
    </blockquote>
    </li>
<?php endforeach; ?>
</ul>