<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/16/2018
 * Time: 1:48 PM
 * @var $prototypeList \yii\db\ActiveRecord
 * @var $model \app\models\Issue
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>


<?php Pjax::begin(['enablePushState' => false,  'id' => 'prototypes', 'linkSelector'=>'.prototype-actions']); ?>
<?php Pjax::end(); ?>
<ul class="nav nav-pills nav-stacked">
<?php foreach ($prototypeList as $prototype): ?>

    <li>
        <a href="<?= Url::to(['prototype/view', 'id' => $prototype->id]) ?>">
        <span class="glyphicon glyphicon-eye-open"></span>
        <?= $prototype->index() ?> <?= $prototype->name ?>
        </a>
    </li>

<?php endforeach; ?>
</ul>
