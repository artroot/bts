<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Notifyrule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notifyrule-form" id="notifyruleContainer<?= $model->id ?>">

    <?php $form = ActiveForm::begin(['id' => 'notifyruleForm' . $model->id, 'action' => ['/admin/notifyrule/update', 'id' => $model->id]]); ?>

    <?php
    $template = [
        'template' => "<td>{input}\n{hint}\n{error}</td>"
    ];
    $chapters = ['Projects', 'Versions', 'Sprints', 'Issues'];
    ?>
        <table class="table table-bordered">
        <tr>
            <td width="100px"><?= $chapters[$model->chapter] ?></td>

            <?= $form->field($model, 'mail', $template)->checkbox() ?>

            <?= $form->field($model, 'telegram', $template)->checkbox() ?>

            <?= $form->field($model, 'owner', $template)->checkbox() ?>

            <?= $form->field($model, 'performer', $template)->checkbox() ?>

            <?= $form->field($model, 'all', $template)->checkbox() ?>

            <?= $form->field($model, 'create', $template)->checkbox() ?>

            <?= $form->field($model, 'update', $template)->checkbox() ?>

            <?= $form->field($model, 'delete', $template)->checkbox() ?>

            <?= $form->field($model, 'done', $template)->checkbox(['disabled' => in_array($model->chapter, [1, 2, 3]) ? false : true]) ?>
 
        </tr>
        </table>

    <?php ActiveForm::end(); ?>

</div>

