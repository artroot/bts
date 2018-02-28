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
<div class="version-index shell">
	<h1><?= Html::img(Url::toRoute(
			['project/get', 'id' => $project->id]),
			['width' => 48, 'class' => 'img-circle', 'style' => 'display: inline-block; padding-left: 4px;']) ?>
		<?= Html::encode($project->name) ?>
		<span style="float: right;">
			<?= Html::a('', ['project/update', 'id' => $project->id], ['class' => 'btn btn-default glyphicon glyphicon-pencil']) ?>
		</span>
	</h1>

	<div class="row">
	<div class="col-md-3 left-nav hidden-xs">
		<?= \Yii::$app->view->renderFile('@app/views/project/left_nav.php',['project' => $project]) ?>
	</div>
	<div class="col-md-9 right-container col-xs-12">
	<?php Pjax::begin(); ?>
	<h1><?= Html::encode($this->title) ?><?= Html::a('Create Version', ['create'], ['class' => 'btn btn-success', 'style' => 'float:right;']) ?>
	</h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
?>
		<br>
		<br>
	</div>
	</div>
</div>


