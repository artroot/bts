<?php
	/**
	 * Created by PhpStorm.
	 * User: art
	 * Date: 2/27/2018
	 * Time: 3:46 PM
	 */
	use yii\helpers\Html;
	use yii\helpers\Url;

	// TODO move to project model
	$items = [
		'version' => 'Versions',
		'task' => 'Tasks',
	];

?>

<div class="list-group">
	<?php foreach ($items as $controller => $name): ?>
	<?= Html::a(
		$name,
		Url::to([ $controller . '/index', 'project_id' => $project->id]),
		['class' => 'list-group-item ' . (Yii::$app->controller->id == $controller ? 'active' : '')]
	) ?>
	<?php endforeach; ?>
</div>
