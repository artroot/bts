<?php

    use app\models\Issuepriority;
    use app\modules\admin\models\Issuestatus;
    use app\models\Issuetype;
    use app\models\Project;
    use app\models\Sprint;
    use app\models\Users;
    use app\models\Version;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\jui\DatePicker;
    use yii\widgets\ActiveForm;



app\assets\AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Issue */
/* @var $form yii\widgets\ActiveForm */
?>

<br>
<div class="issue-form">

    <?php $form = ActiveForm::begin(['id' => 'issueForm', 'action' => $action]); ?>

    <div class="row">
        <div class="col-sm-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        </div>
        <div class="col-sm-4">
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
                }), ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
                <?= $form->field($model, 'owner_id', $template)->dropDownList(ArrayHelper::map(Users::find()->all(), 'id', function ($user) {
                    return $user->first_name . ' ' . $user->last_name;
                }), ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'detected_version_id', $template)->dropDownList(ArrayHelper::map(
                Version::find()->where(['project_id' => $model->project_id])->all(), 'id', 'name'),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'resolved_version_id', $template)->dropDownList(ArrayHelper::map(
                    Version::find()->where(['project_id' => $model->project_id])->all(), 'id', 'name'),
                    ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>
            <?= $form->field($model, 'sprint_id', $template)->dropDownList(ArrayHelper::map(
                Sprint::find()->all(), 'id', 'name'),
                ['prompt' => 'Not set', 'class' => 'btn btn-link']) ?>

            <?= $form->field($model, 'deadline', $template)->textInput() ?>
                <script>
                    $(document).ready(function () {
                        $('#issue-deadline').datetimepicker({
                            datepicker:true,
                            format:'Y-m-d H:i',
                            onSelectDate: function(data, item){
                                $(item.closest("form")).submit();
                            }
                        });
                    });
                </script>

            </table>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>



</div>
