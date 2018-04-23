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
            <?= count($model->getTree())-1 == $key ? ($key == 0 ? $model->index() : $folder) : sprintf('<a class="prototype-actions" href="%s" data-pjax="prototypes">%s</a>',
                Url::to(['prototype/filebrowser', 'id' => $model->id, 'back_to' => implode('/', $urlPath)]), $key == 0 ? $model->index() : $folder) ?>
        </li>
    <?php endforeach; ?>
</ol>
<div class="file-browser thumbnail" style="max-height: 300px; background-color: #f5f5f5; overflow: auto;">
    <?= $form->field($model, 'index_file_name')->radioList($model->getPrototypeFiles(), [
        'item' => function($index, $label, $name, $checked, $value) use ($model) {
            $return = '<p><label>';
            $valuePath = $model->getTree();
            if(!is_dir(Yii::$app->basePath . '/web/prototypes/' . implode('/', $valuePath) . '/' .  $label)) {
                array_shift($valuePath);
                array_push($valuePath, $label);
                $return .= '<span class="glyphicon glyphicon-file"></span> <input type="radio" ' . ($model->index_file_name == implode('/', $valuePath) ? 'checked' : '') . ' name="' . $name . '" value="' .  implode('/', $valuePath) . '" tabindex="3"> <span>' . $label   . '</span>';
            }else{
                array_push($valuePath, $label);
                $return .= '<a href="' . Url::to(['prototype/filebrowser', 'id' => $model->id, 'browse_to' => implode('/', $valuePath)]) .
                    '" class="prototype-actions" data-pjax="prototypes"><span class="glyphicon glyphicon-folder-open"></span> &nbsp; <span>' . $label . '</span></a>';
            }
            $return .= '</label></p>';

            return $return;
        }
    ])->label(false) ?>
</div>
