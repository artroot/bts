<?php
	/**
	 * Created by PhpStorm.
	 * User: art
	 * Date: 3/6/2018
	 * Time: 5:39 PM
	 */
	use yii\widgets\Pjax;

?>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#email" aria-controls="email" role="tab" data-toggle="tab">Email</a></li>
	<li role="presentation"><a href="#telegram" aria-controls="telegram" role="tab" data-toggle="tab">Telegram</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="email">
		<p>Email settings</p>
	</div>
	<div role="tabpanel" class="tab-pane" id="telegram">
		<div id="telegramForm">
		<?= @$telegramForm ?>
		</div>
	</div>
</div>

<?php
	Pjax::widget([
		'id' => 'telegramForm',  // response goes in this element
		'enablePushState' => false,
		'enableReplaceState' => false,
		'formSelector' => '#w0',// this form is submitted on change
		'submitEvent' => 'submit',
	]);

?>
