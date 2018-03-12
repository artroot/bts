<?php
	/**
	 * Created by PhpStorm.
	 * User: art
	 * Date: 3/11/2018
	 * Time: 9:58 PM
	 */
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;

	Pjax::widget([
		'id' => 'userSettingsForm',  // response goes in this element
		'enablePushState' => false,
		'enableReplaceState' => false,
		'formSelector' => '#userForm',// this form is submitted on change
		'submitEvent' => 'submit',
	]);

?>


<?php
	Modal::begin([
		'header' => '<span style="float: left;">Settings</span><div id="navbar-settings" align="center">
	<div class="btn-group" role="group">
		<a href="#mainSettings" class="btn btn-default">Main</a>
		<a href="#notifySettings" class="btn btn-default">Notifications</a>
	</div>
</div>',
		'id' => 'userSettingsModal',
		'size' => 'modal-lg',
		'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
	]); ?>

	<div id="userSettingsForm" data-spy="scroll" style="position: relative; height: 400px; overflow: auto;" data-target="#navbar-settings">
		<?= @$userForm ?>
	</div>

	<?php Modal::end(); ?>

<script>
	$('#userSettingsModal').modal('show');
</script>
