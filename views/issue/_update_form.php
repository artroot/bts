<?php

use app\models\Comment;
use app\models\Issuepriority;
    use app\models\Issuestatus;
    use app\models\Issuetype;
    use app\models\Project;
    use app\models\Sprint;
use app\models\State;
use app\models\Users;
    use app\models\Version;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\jui\DatePicker;
    use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Issue */
/* @var $form yii\widgets\ActiveForm */


$issueName = Project::findOne(['id' => $model->project_id])->name . '-' . $model->id;

?>
<br>
<div class="issue-form">




    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'issueForm', 'action' => $action]); ?>
        <div class="col-md-12">
            <a class="btn btn-default btn-xs" onclick="$('#issue_descr').toggle('fast'); $('#issue-name-s').toggle('fast');">
                <span class="glyphicon glyphicon-edit"></span>
            </a>
            <h3>
                <?= Issuestatus::findOne([
                    'id' => $model->issuestatus_id
                ])->state_id == State::DONE ? sprintf('<s>%s</s>', $issueName) : $issueName  ?>
                <span> <?= $model->name ?></span>
            </h3>
            <ol class="issue-nav">
                <li><?= sprintf('Created by (%s): %s', Users::findOne(['id' => $model->owner_id])->username ,$model->create_date) ?></li>
                <li><?= sprintf('Resolved: %s', $model->finish_date) ?></li>
            </ol>
        </div>

        <div class="col-lg-3 col-sm-5 col-md-3 col-sm-push-7 col-md-push-9 panel-default">
            <div class="panel-heading" style="font-size: smaller;">
            <table>
                <?php
                    $template = [
                        'template' => "<tr><td>{label}</td><td>{input}\n{hint}\n{error}</td></tr>"
                    ];
                    ?>
            <?= $form->field($model, 'project_id', $template)->dropDownList(ArrayHelper::map(Project::find()->all(), 'id', 'name'), ['class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'issuepriority_id', $template)->dropDownList(ArrayHelper::map(Issuepriority::find()->all(), 'id', 'name'), ['class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'issuetype_id', $template)->dropDownList(ArrayHelper::map(Issuetype::find()->all(), 'id', 'name'), ['class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'issuestatus_id', $template)->dropDownList(ArrayHelper::map(Issuestatus::find()->all(), 'id', 'name'), ['class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'performer_id', $template)->dropDownList(ArrayHelper::map(Users::find()->all(), 'id', 'username'),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'detected_version_id', $template)->dropDownList(ArrayHelper::map(
                Version::find()->where(['project_id' => $model->project_id])->all(), 'id', 'name'),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'resolved_version_id', $template)->dropDownList(ArrayHelper::map(
                    Version::find()->where(['project_id' => $model->project_id])->all(), 'id', 'name'),
                    ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'sprint_id', $template)->dropDownList(ArrayHelper::map(
                Sprint::find()->all(), 'id', 'name'),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>

            <?= $form->field($model, 'deadline', [
                    'template' => "<tr><td>{label}</td><td></td></tr><tr><td colspan='2'>{input}\n{hint}\n{error}</td></tr>"
                ])->input('datetime-local', ['style' => 'font-size: x-small;']) ?>
            </table>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="col-lg-9 col-sm-7 col-md-9 col-sm-pull-5 col-md-pull-3">


                    <span id="issue-name-s" style="display: none;">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                    </span>

            <p id="issue_descr"><?= nl2br($model->description) ?></p>
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#">Comments</a></li>
                <li role="presentation"><a href="#">Log</a></li>
                <li role="presentation"><a href="#">Relate</a></li>
            </ul>
            <?= $this->renderAjax('@app/views/comment/index', [
                'issue_id' => $model->id
            ]) ?>
        </div>
    </div>



    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'finish_date')->textInput() ?>






    <?= $form->field($model, 'version_id')->textInput() ?>








    <?= $form->field($model, 'parentissue_id')->textInput() ?>

    <?= $form->field($model, 'relatedissue_id')->textInput() ?>







</div>
