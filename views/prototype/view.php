<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Prototype */

$this->title = $model->index() . ' ' . $model->name;

$this->params['titleItems'] = [
    'label' => $this->title,
    'items' => [
        [
            'label' => '<center>' . $model->name . '</center>'
        ],
        [
            'label' => '<li class="divider"></li>'
        ],
        [
            'label' => '<li>' . Html::a('Delete prototype', ['prototype/delete', 'id' => $model->id], ['data' => [
                    'confirm' => 'Are you sure you want to delete this prototype?',
                    'method' => 'post',
                ]]) . '</li>',

        ],
    ]
];

?>
<div class="prototype-view">
    <iframe width="100%" style="border: 0; position: fixed; left: 0; overflow: auto; width: 100%; height: 92%;" src="<?= $model->path ?>"></iframe>
</div>
<script>
    $('body').css('overflow', 'hidden');
</script>
