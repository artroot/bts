<?php

use app\models\Attachment;
use app\models\Comment;
use app\models\Issuepriority;
    use app\modules\admin\models\Issuestatus;
    use app\models\Issuetype;
    use app\models\Project;
use app\models\Prototype;
use app\models\PrototypeSearch;
use app\models\Relation;
use app\models\RelationSearch;
use app\models\Sprint;
use app\models\State;
use app\models\Users;
    use app\models\Version;
use app\modules\admin\models\Log;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
    use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Issue */
/* @var $form yii\widgets\ActiveForm */
/*
$this->registerJsFile('/assets/f514d8f4/jquery.js', ['position' => \yii\web\View::POS_HEAD]);
*/

app\assets\AppAsset::register($this);

$this->title = $model->index();

$this->params['titleItems'] = [
    'label' => $this->title,
    'items' => [
        [
            'label' => '<center><span class="badge text-capitalize" title="'. @$model->getPriority()->name .'" style="background-color: '. @$model->getPriority()->color . ';">'. @$model->getPriority()->name . '</span>
                <span class="badge text-capitalize">'. @$model->getType()->name . '</span>
                <span class="badge text-capitalize">'. @$model->getStatus()->name . '</span></center>'
        ],
        [
            'label' => sprintf('Created by %s: %s', $model->getOwner()->first_name . ' ' . $model->getOwner()->last_name ,$model->create_date)
        ],
        [
            'label' => '<li class="divider"></li>'
        ],
        [
            'label' => '<li>' . Html::a('Edit Subject and Description', '#', ['onclick' => '$(\'#issue_descr\').toggle(\'fast\'); $(\'#issue-name-s\').toggle(\'fast\');']) . '</li>',
        ],
        [
            'label' => '<li>' . Html::a('Add relation', '#', ['data-toggle' => 'modal', 'data-target' => '#associated']) . '</li>',
        ],
        [
            'label' => '<li>' . Html::a('Delete issue', ['issue/delete', 'id' => $model->id], ['data' => [
                    'confirm' => 'Are you sure you want to delete this issue?',
                    'method' => 'post',
                ]]) . '</li>',

        ],
    ]
];

$attachmentModel = new Attachment();
$attachmentModel->issue_id = $model->id;
?>
<br>
<div class="issue-form">

    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'issueForm', 'action' => $action]); ?>
        <div class="col-md-12">
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
                <li title="<?= $model->getOwner()->username ?>"><?= sprintf('Created by %s: %s', $model->getOwner()->first_name . ' ' . $model->getOwner()->last_name ,$model->create_date) ?></li>
                <?php if (!empty($model->finish_date) and $model->finish_date != '0000-00-00 00:00:00'): ?>
                <li><?= sprintf('Resolved: %s', $model->finish_date) ?></li>
                <?php endif; ?>
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

                <?= $form->field($model, 'progress_time', [
                    'template' => "<tr><td>{label}</td><td><h5><span style='margin-left: 15px;' class='label label-primary'>" . $model->getProgressTime() . "</span></h5></td></tr>"
                ]) ?>

                <?= $form->field($model, 'performer_id', $template)->dropDownList(ArrayHelper::map(Users::find()->all(), 'id', function ($user) {
                    return $user->first_name . ' ' . $user->last_name;
                }), ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
                <?= $form->field($model, 'owner_id', $template)->dropDownList(ArrayHelper::map(Users::find()->all(), 'id', function ($user) {
                    return $user->first_name . ' ' . $user->last_name;
                }), ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'detected_version_id', $template)->dropDownList(ArrayHelper::map(
                Version::find()->where(['project_id' => $model->project_id])->all(), 'id', 'name'),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'resolved_version_id', $template)->dropDownList(ArrayHelper::map(
                    Version::find()->where(['project_id' => $model->project_id])->all(), 'id', ['name']),
                    ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'sprint_id', $template)->dropDownList(ArrayHelper::map(
                Sprint::find()->all(), 'id', 'name'),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>

                <?= $form->field($model, 'deadline', $template)->textInput() ?>
            </table>

                <script>
                    $(document).ready(function () {
                        $(['#issue-deadline', '#issue-start_date']).datetimepicker({
                            datepicker:true,
                            timepicker:true,
                            format:'Y-m-d H:i'
                        });
                    });
                </script>
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
                    <a href="#comments" class="btn-xs" aria-controls="comments" role="tab" data-toggle="tab">Comments
                        <?php if ($commentsCount = Comment::find()->where(['issue_id' => $model->id])->count() and $commentsCount > 0): ?>
                        <span class="badge"><?= $commentsCount ?></span>
                        <?php endif; ?>
                    </a></li>
                <li role="presentation"><a href="#log" class="btn-xs" aria-controls="log" role="tab" data-toggle="tab">Log</a></li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle btn-xs" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        Relations
                        <?php if ($model->getAssociateWith()->count()+$model->getRelatedFor()->count() > 0): ?>
                            <span class="badge"><?= $model->getAssociateWith()->count()+$model->getRelatedFor()->count() ?></span>
                        <?php endif; ?>
                        <span class="caret"></span>
                    </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#related_for" aria-controls="related_for" role="tab" data-toggle="tab">Related for
                                    <?php if ($model->getRelatedFor()->count() > 0): ?>
                                        <span class="badge"><?= $model->getRelatedFor()->count() ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li>
                                <a href="#associated_with" aria-controls="associated_with" role="tab" data-toggle="tab">Associated with
                                    <?php if ($model->getAssociateWith()->count() > 0): ?>
                                        <span class="badge"><?= $model->getAssociateWith()->count() ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <?= Html::a('Add relation', '#', ['data-toggle' => 'modal', 'data-target' => '#associated']) ?>
                            </li>
                        </ul>
                    </li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle btn-xs" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        Additions 
                        <?php if ($model->getPrototypes()->count()+$model->getAttachments()->count() > 0): ?>
                            <span class="badge"><?= $model->getPrototypes()->count()+$model->getAttachments()->count() ?></span>
                        <?php endif; ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#additions" aria-controls="additions" role="tab" data-toggle="tab">
                                Attachments & Prototypes
                                <?php if ($model->getPrototypes()->count()+$model->getAttachments()->count() > 0): ?>
                                    <span class="badge"><?= $model->getPrototypes()->count()+$model->getAttachments()->count() ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <?= Html::a('Add prototype', ['prototype/create', 'issue_id' => $model->id], ['data-pjax' => 'prototypes', 'class' => 'prototype-actions']) ?>
                        </li>
                        <li>
                            <a>
                                <?php $form = ActiveForm::begin([
                                    'id' => 'attachmentForm',
                                    'action' => Url::to(['attachment/create']),
                                    'options' => ['enctype' => 'multipart/form-data']
                                ]); ?>
                                <?= $form->field($attachmentModel, 'issue_id')->hiddenInput()->label(false) ?>
                                <label style="font-weight: unset; cursor: pointer;">
                                    Add attachment
                                    <input type="file" id="attachment-file" style="display: none;" form="attachmentForm" name="Attachment[file]">
                                </label>
                                <?php ActiveForm::end(); ?>
                                <div class="progress hidden" id="uploadingProgress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                        Uploading file...
                                    </div>
                                </div>
                            </a>
                            <script>
                                $('#attachment-file').change(function() {
                                    $('#attachmentForm').addClass('hidden');
                                    $('#uploadingProgress').removeClass('hidden');
                                });
                            </script>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="comments">
                    <?= $this->render('@app/views/comment/index', [
                        'issue_id' => $model->id
                    ]) ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="log">
                    <?= $this->render('@app/modules/admin/views/log/index', [
                        'logModels' => Log::find()->where(['model' => $model->className()])->andWhere(['model_id' => $model->id])->orderBy(['date' => SORT_DESC])->all()
                    ]) ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="related_for">
                    <?= $this->render('@app/views/relation/index_related_for', [
                        'searchModel' => $searchModelRelatedFor = new RelationSearch(),
                        'dataProvider' => $searchModelRelatedFor->search(['RelationSearch' => ['to_issue' => $model->id], '_pjax' => '#w_related_for']),
                    ]) ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="associated_with">
                    <?= $this->render('@app/views/relation/index_associated_with', [
                        'searchModel' => $searchModelAssociatedWith = new RelationSearch(),
                        'dataProvider' => $searchModelAssociatedWith->search(['RelationSearch' => ['from_issue' => $model->id], '_pjax' => '#w_associated_with']),
                    ]) ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="additions">
                    <?= $this->render('@app/views/prototype/list', [
                        'prototypeList' => Prototype::find()->where(['issue_id' => $model->id])->orderBy(['id' => SORT_DESC])->all(),
                        'model' => $model
                    ]) ?>
                    <?= $this->render('@app/views/attachment/list', [
                        'attachments' => Attachment::find()->where(['issue_id' => $model->id])->orderBy(['id' => SORT_DESC])->all()
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <?php
        Pjax::widget([
            'id' => 'additions',  // response goes in this element
            'enablePushState' => false,
            'enableReplaceState' => false,
            'formSelector' => '#attachmentForm',// this form is submitted on change
            'submitEvent' => 'change',
        ]);
        ?>
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
</div>

<?php if($model->start_date): ?>

    <?php
    Modal::begin([
        'id' => 'issueStartTimeModal',
        'size' => 'modal-sm'
    ]); ?>

    <?= $form->field($model, 'start_date', [
        'template' => "{label}{input}\n{hint}\n{error}\r\n<input type='submit' form='issueForm' data-dismiss='modal' data-pjax='issueUpForm' class='btn btn-success' value='OK'>"
    ])->textInput(['data-pjax' => '0', 'form' => 'issueForm']) ?>

    <?php Modal::end(); ?>

    <script>
        $('#issueStartTimeModal').modal('show');
    </script>

<?php endif; ?>


<?php Pjax::begin(['enablePushState' => false,  'id' => 'prototypes', 'linkSelector'=>'.prototype-actions']); ?>
<?php Pjax::end(); ?>
