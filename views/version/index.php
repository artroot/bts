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
	$this->params['breadcrumbs'][] = $project->name;
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="version-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php Pjax::begin(); ?>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create Version', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			//'id',
			// 'project_id',
			'name',
			//'create_date',
			'finish_date',
			'description:ntext',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>

<?php

	$data = [
		100,
		97,
		95,
		90,
		90,
		90,
		80,
		37,
		22,
		0
	];

	$svg = SVG::generate($data, 10);

	echo $this->render('graph', [
		'graphs' => [
			$svg->getIdeal(),
			$svg->getCoords(),
		],
		'scales' => $svg->getScales(),
	]);
