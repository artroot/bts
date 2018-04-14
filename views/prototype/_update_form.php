<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Prototype */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="prototype-update">
<div class="prototype-form">

    <?php $form = ActiveForm::begin(['id' => 'prototypeForm', 'action' => $action, 'options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="thumbnail" style="max-height: 300px; overflow: auto;">


        <?= $form->field($model, 'index_file_name')->radioList($model->getPrototypeFiles(), [
            'item' => function($index, $label, $name, $checked, $value) use ($model) {
                $return = '<p><label>';
                if(!is_dir(Yii::$app->basePath . '/web' . $model->path . $label)) {
                    $return .= '<span class="glyphicon glyphicon-file"></span> <input type="radio" ' . ($model->index_file_name == $label ? 'checked' : '') . ' name="' . $name . '" value="' . $label . '" tabindex="3"> <span>' . $label   . '</span>';
                }else{
                    $return .= '<span class="glyphicon glyphicon-folder-open"></span> <span>' . $label . '</span>';
                }
                $return .= '</label></p>';

                return $return;
            }
        ])->label(false) ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
