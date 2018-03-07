<?php

namespace app\modules\admin\controllers;

use app\models\UsersSearch;
use app\modules\admin\models\Telegram;
use Predis\Client;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;

/**
 * SettingsController implements the CRUD actions for Project model.
 */
class SettingsController extends DefaultController
{

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect('settings/main');
    }

    public function actionMain()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('index', [
                'data' => $this->renderPartial('main'),
                'active' => 'main'
            ]);
        }else{
            return $this->render('index', [
                'data' => $this->renderPartial('main'),
                'active' => 'main'
            ]);
        }
    }

    public function actionNotification()
    {

        if (Telegram::find()->one()){
            $model = Telegram::find()->one();
        }else {
            $model = new Telegram();
        }

        if (empty($model->base_url)) $model->base_url = 'https://api.telegram.org/bot';
        $telegramForm = $this->renderPartial('@app/modules/admin/views/telegram/update', [
            'model' => $model,
            'webHookStatus' => Telegram::getWebhookInfo(),
        ]);


        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('index', [
                'data' => $this->renderPartial('notification', [
                    'telegramForm' => $telegramForm
                ]),
                'active' => 'notification'
            ]);
        }else{
            return $this->render('index', [
                'data' => $this->renderPartial('notification', [
                    'telegramForm' => $telegramForm
                ]),
                'active' => 'notification'
            ]);
        }
    }

    public function actionUsers()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('index', [
                'data' => $this->renderPartial('@app/modules/admin/views/users/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]),
                'active' => 'users'
            ]);
        }else{
            return $this->render('index', [
                'data' => $this->renderPartial('@app/modules/admin/views/users/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]),
                'active' => 'users'
            ]);
        }
    }
}
