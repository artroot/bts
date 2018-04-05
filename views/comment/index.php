<?php

use app\models\Comment;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $comments app\models\Comment */
/* @var $comment app\models\Comment */
/* @var $issue_id */


$model = new Comment();
$model->issue_id = $issue_id;
$model->user_id = Yii::$app->user->identity->getId();

?>
<div class="comment-index">

    <?= $this->renderAjax('create', [
        'model' => $model
    ]) ?>

    <?php foreach (Comment::find()->where(['issue_id' => $issue_id])->orderBy(['id' => SORT_DESC])->all() as $comment): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-comment"></span>
            <span title="<?= $comment->getUser()->one()->username ?>"><?= $comment->getUser()->one()->first_name ?> <?= $comment->getUser()->one()->last_name ?></span>
            <span> / </span>
            <span><?= $comment->create_date ?></span>
            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['comment/delete', 'id' => $comment->id], [
                'style' => 'float: right;',
                'class' => 'btn btn-link btn-xs',
                'encodeLabels' => false,
                'data' => [
                    'confirm' => 'Are you sure you want to delete this comment?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="panel-body">
            <?= nl2br($comment->text) ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

