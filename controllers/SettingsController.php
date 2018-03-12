<?php
	/**
	 * Created by PhpStorm.
	 * User: art
	 * Date: 3/11/2018
	 * Time: 9:55 PM
	 */

	namespace app\controllers;

use app\models\Users;
use Yii;

class SettingsController extends DefaultController
{

	public function actionIndex()
	{
		$user = Users::findOne(['id' => Yii::$app->user->identity->id]);

		return $this->renderAjax('index',
			[
				'user' => $user,
				'userForm' => $this->renderPartial('@app/modules/admin/views/users/update', [
					'model' => Users::findOne(['id' => Yii::$app->user->identity->id])
				])
			]
			);
	}
	
}