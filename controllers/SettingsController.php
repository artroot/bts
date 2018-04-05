<?php
	/**
	 * Created by PhpStorm.
	 * User: art
	 * Date: 3/11/2018
	 * Time: 9:55 PM
	 */

	namespace app\controllers;

use app\models\SignupForm;
use app\models\User;
use app\models\Users;
use Yii;

class SettingsController extends DefaultController
{

	public function actionIndex($id = false)
	{
		$user = Users::findOne(['id' => Yii::$app->user->identity->getId()]);

		if ($id and $user->usertype_id = 1){
			$user = Users::findOne(['id' => $id]);
		}

		return $this->renderAjax('index',
			[
				'user' => $user,
				'userForm' => $this->renderPartial('@app/modules/admin/views/users/update', [
					'model' => $user
				])
			]
			);
	}

	public function actionCreate()
	{
		$model = new Users();

		return $this->renderAjax('index',
			[
				'user' => $model,
				'userForm' => $this->renderPartial('@app/modules/admin/views/users/create', [
					'model' => $model,
					'action' => '/admin/users/create',
				])
			]
		);
	}
	
}