<?php

	use app\components\SVG;
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\helpers\Url;
	use yii\widgets\Pjax;

	/* @var $this yii\web\View */
	/* @var $searchModel app\models\VersionSearch */
	/* @var $dataProvider yii\data\ActiveDataProvider */

	$this->title = 'Versions';
	/*$this->params['breadcrumbs'][] = $project->name;
	$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="version-index">
	<h1><?= Html::encode($this->title) ?>

		<?= Html::a('Create Version',
				['version/create', 'project_id' => $project->id],
				['class' => 'btn btn-success version-actions', 'style' => 'float:right;', 'data-pjax' => 'versions']) ?>
	</h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'class' => 'table-condensed',
		'columns' => [


			//'id',
			// 'project_id',
			//'status',
			[
				'label' => 'Status',
				'content' => function($data){
					return $data->getStatusIcon();
				}
			],
			[
				'attribute'=>'name',
				'label'=>'Name',
				'format'=>'raw',
				'content'=>function($data){
					return Html::a($data->name, ['version/view', 'id' => $data->id]);
				},
				'filter' => $searchModel
			],
			'start_date',
			'finish_date',
			'description:ntext',

			['class' => 'yii\grid\ActionColumn',
				'buttons' => [
				'update' => function ($url, $model) {
					return Html::a(
						'<span class="glyphicon glyphicon-pencil"></span>',
						['version/update', 'id' => $model->id],
						['class' => 'version-actions', 'data-pjax' => 'versions']);
				},
			], 'template' => '{update} {delete}'],
		],
	]); ?>

</div>



