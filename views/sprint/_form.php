<?php

use app\models\Project;
use app\models\Version;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


app\assets\AppAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\models\Sprint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sprint-form">

    <?php $form = ActiveForm::begin(['id' => 'sprintForm', 'action' => @$action]); ?>

    <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(Project::find()->all(), 'id', 'name')) ?>

    <?=
    $form->field($model, 'version_id')->dropDownList(
        ArrayHelper::map(
            Version::find()
                ->where(['project_id' => $model->project_id])
                ->andWhere(['status' => 0])
                ->orderBy(['id' => SORT_DESC])
                ->all(),
            'id',
            'name'
        )
        ,['prompt' => 'Not set', 'data-pjax' => false]
    )
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'data-pjax' => false]) ?>

    <?= $form->field($model, 'start_date')->textInput(['data-pjax' => false]) ?>
    <?= $form->field($model, 'finish_date')->textInput(['data-pjax' => false]) ?>


    <script>
        $(document).ready(function () {
            $(['#sprint-start_date', '#sprint-finish_date']).datetimepicker({
                datepicker:true,
                format:'Y-m-d H:i'
            });
        });
    </script>

    <div class="form-group">
        <?= Html::button('Save', ['class' => 'btn btn-success', 'onclick' => '$(\'#sprintForm\').attr(\'action\', \'/sprint/update?id=' . $model->id . '\').submit();']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



