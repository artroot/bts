<?php

use app\models\Comment;
use app\models\Issuepriority;
    use app\models\Issuestatus;
    use app\models\Issuetype;
    use app\models\Project;
use app\models\Relation;
use app\models\RelationSearch;
use app\models\Sprint;
use app\models\State;
use app\models\Users;
    use app\models\Version;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
    use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Issue */
/* @var $form yii\widgets\ActiveForm */



?>
<br>
<div class="issue-form">




    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'issueForm', 'action' => $action]); ?>
        <div class="col-md-12">
            <a class="btn btn-default btn-xs" title="Edit Subject and Description" onclick="$('#issue_descr').toggle('fast'); $('#issue-name-s').toggle('fast');">
                <span class="glyphicon glyphicon-edit"></span>
            </a>
            <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['issue/delete', 'id' => $model->id], [
                'class' => 'btn btn-default btn-xs',
                'encodeLabels' => false,
                'title' => 'Delete this Issue',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this issue?',
                    'method' => 'post',
                ],
            ]) ?>

            <a class="btn btn-default btn-xs" title="Add Relations" data-toggle="modal" data-target="#associated">
                <span class="glyphicon glyphicon-link"></span>
            </a>

            <h3>
                <span class="badge text-uppercase" title="<?= @$model->getPriority()->name ?>" style="background-color: <?= @$model->getPriority()->color ?>;"><?= substr(@$model->getPriority()->name, 0, 1) ?></span>
                <span class="badge text-capitalize"><?= @$model->getType()->name ?></span>
                <span class="badge text-capitalize"><?= @$model->getStatus()->name ?></span>
                <?php if(Issuestatus::findOne(['id' => $model->issuestatus_id])->state_id == State::DONE): ?>
                       <span class="text-muted">
                            <s><?= Html::a($model->index(),['issue/update', 'id' => $model->id], ['class' => 'btn-link']) ?></s> <?= $model->name ?>
                       </span>
                <?php else: ?>
                        <span>
                            <?= Html::a($model->index(),['issue/update', 'id' => $model->id], ['class' => 'btn-link']) ?> <?= $model->name ?>
                       </span>
                <?php endif; ?>
            </h3>
            <ol class="issue-nav">
                <li title="<?= Users::findOne(['id' => $model->owner_id])->username ?>"><?= sprintf('Created by %s: %s', Users::findOne(['id' => $model->owner_id])->first_name . ' ' . Users::findOne(['id' => $model->owner_id])->last_name ,$model->create_date) ?></li>
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
            <?= $form->field($model, 'performer_id', $template)->dropDownList(ArrayHelper::map(Users::find()->all(), 'id', function ($user) {
                    return $user->first_name . ' ' . $user->last_name;
                }),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'detected_version_id', $template)->dropDownList(ArrayHelper::map(
                Version::find()->where(['project_id' => $model->project_id])->all(), 'id', 'name'),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'resolved_version_id', $template)->dropDownList(ArrayHelper::map(
                    Version::find()->where(['project_id' => $model->project_id])->all(), 'id', ['name']),
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

        <div class="col-lg-9 col-sm-7 col-md-9 col-sm-pull-5 col-md-pull-3">


                    <span id="issue-name-s" style="display: none;">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                    </span>

            <p id="issue_descr"><?= nl2br($model->description) ?></p>

        </div>

        <?php ActiveForm::end(); ?>
        <div class="col-lg-9 col-sm-7 col-md-9 col-sm-pull-5 col-md-pull-3">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comments
                        <?php if ($commentsCount = Comment::find()->where(['issue_id' => $model->id])->count() and $commentsCount > 0): ?>
                        <span class="badge"><?= $commentsCount ?></span>
                        <?php endif; ?>
                    </a></li>
                <li role="presentation"><a href="#log" aria-controls="log" role="tab" data-toggle="tab">Log</a></li>
                <li role="presentation"><a href="#related_for" aria-controls="related_for" role="tab" data-toggle="tab">Related for
                        <?php if ($relatedForCount = Relation::find()->where(['to_issue' => $model->id])->count() and $relatedForCount > 0): ?>
                            <span class="badge"><?= $relatedForCount ?></span>
                        <?php endif; ?>
                    </a></li>
                <li role="presentation"><a href="#associated_with" aria-controls="associated_with" role="tab" data-toggle="tab">Associated with
                        <?php if ($associatedWithCount = Relation::find()->where(['from_issue' => $model->id])->count() and $associatedWithCount > 0): ?>
                            <span class="badge"><?= $associatedWithCount ?></span>
                        <?php endif; ?>
                    </a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="comments">
                    <?= $this->renderAjax('@app/views/comment/index', [
                        'issue_id' => $model->id
                    ]) ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="log">...</div>
                <div role="tabpanel" class="tab-pane" id="related_for">
                    <?= $this->renderAjax('@app/views/relation/index_related_for', [
                        'searchModel' => $searchModelRelatedFor = new RelationSearch(),
                        'dataProvider' => $searchModelRelatedFor->search(['RelationSearch' => ['to_issue' => $model->id], '_pjax' => '#w_related_for']),
                    ]) ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="associated_with">
                    <?= $this->renderAjax('@app/views/relation/index_associated_with', [
                        'searchModel' => $searchModelAssociatedWith = new RelationSearch(),
                        'dataProvider' => $searchModelAssociatedWith->search(['RelationSearch' => ['from_issue' => $model->id], '_pjax' => '#w_associated_with']),
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-offset-3 col-sm-offset-5 col-md-offset-3"></div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="associated" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Associated with</h4>
                </div>
                <div class="modal-body">
                    <form id="searchIssue" action="<?= Url::to(['issue/search']) ?>" method="get">
                        <input type="hidden" name="issue_id" value="<?= $model->id ?>">
                        <input type="hidden" name="IssueSearch[project_id]" value="<?= $model->project_id ?>">
                        <input type="text" name="IssueSearch[name]" class="form-control" placeholder="Search Issue">
                    </form>
                    <div class="relation-create">
                    <div class="relation-form">
                        <form id="createRelationForm" action="<?= Url::to(['relation/create']) ?>" method="post">
                            <div id="searchResult">

                            </div>
                        </form>
                    </div>
                    </div>
                   <?php
                    Pjax::widget([
                        'id' => 'searchResult',  // response goes in this element
                        'enablePushState' => false,
                        'enableReplaceState' => false,
                        'formSelector' => '#searchIssue',// this form is submitted on change
                        'submitEvent' => 'keyup',
                    ]);
                    ?>
                </div>
                <div class="modal-footer">
                    <?= Html::hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), ['form' => 'createRelationForm']) ?>
                    <?= Html::textarea('Relation[comment]', '', [
                        'form' => 'createRelationForm',
                        'class' => 'form-control',
                        'placeholder' => 'Comment'
                    ]) ?>
                    <?= Html::hiddenInput('Relation[from_issue]', $model->id, ['form' => 'createRelationForm']) ?>
                    <br>
                    <button type="submit" form="createRelationForm" class="btn btn-primary">Relate</button>
                </div>
            </div>
        </div>
    </div>


    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'finish_date')->textInput() ?>






    <?= $form->field($model, 'version_id')->textInput() ?>








    <?= $form->field($model, 'parentissue_id')->textInput() ?>

    <?= $form->field($model, 'relatedissue_id')->textInput() ?>







</div>
