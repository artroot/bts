<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/15/2018
 * Time: 10:57 AM
 * @var $this yii\web\View
 * @var $model app\models\Prototype
 * @var $form yii\widgets\ActiveForm
 */
use yii\helpers\Url;
use yii\widgets\Pjax;

$urlPath = [];

?>
<label>Choose the prototype index file</label>
<ol class="breadcrumb">
    <?php foreach($model->getTree() as $key => $folder): ?>
        <?php
        $urlPath[] = $folder;
        ?>
        <li class="<?= count($model->getTree())-1 == $key ? 'active' : '' ?>">
            <?= count($model->getTree())-1 == $key ? $folder : sprintf('<a href="%s" data-pjax="prototypeMainForm">%s</a>',
                Url::to(['prototype/filebrowser', 'id' => $model->id, 'back_to' => implode('/', $urlPath)]), $folder) ?>
        </li>
    <?php endforeach; ?>
</ol>
<div class="file-browser thumbnail" style="max-height: 300px; overflow: auto;">
    <?= $form->field($model, 'index_file_name')->radioList($model->getPrototypeFiles(), [
        'item' => function($index, $label, $name, $checked, $value) use ($model) {
            $return = '<p><label>';
            if(!is_dir(Yii::$app->basePath . '/web' . $model->path . '/' .  $label)) {
                $valuePath = $model->getTree();
                array_shift($valuePath);
                array_push($valuePath, $label);
                $return .= '<span class="glyphicon glyphicon-file"></span> <input type="radio" ' . ($model->index_file_name == implode('/', $valuePath) ? 'checked' : '') . ' name="' . $name . '" value="' .  implode('/', $valuePath) . '" tabindex="3"> <span>' . $label   . '</span>';
            }else{
                $urlPath = $model->getTree();
                array_push($urlPath, $label);
                $return .= '<span class="glyphicon glyphicon-folder-open"></span> 
<a href="' . Url::to(['prototype/filebrowser', 'id' => $model->id, 'browse_to' => implode('/', $urlPath)]) . '" data-pjax="prototypeMainForm">' . $label . '</a>';
            }
            $return .= '</label></p>';

            return $return;
        }
    ])->label(false) ?>
</div>
