<?php

use yii\helpers\Html;
    use yii\widgets\Pjax;


    /* @var $this yii\web\View */
/* @var $model app\models\Issue */

$this->title = 'Create Issue';
/*$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="issue-create" id="issueCrForm">

    <?= $this->render('_form', [
        'model' => $model,
        'action' => $action
    ]) ?>

</div>
    <div class="form-group">
        <?= Html::button('Save', ['class' => 'btn btn-success', 'onclick' => '$(\'#issueForm\').attr(\'action\', \'/issue/create\').submit();']) ?>
    </div>
<?php
    Pjax::widget([
        'id' => 'issueCrForm',  // response goes in this element
        'enablePushState' => false,
        'enableReplaceState' => false,
        'formSelector' => '#issueForm',// this form is submitted on change
        'submitEvent' => 'change',
    ]);

?>