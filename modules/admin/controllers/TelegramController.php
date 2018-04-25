<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Telegram;
use app\modules\admin\models\TelegramSearch;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * TelegramController implements the CRUD actions for Telegram model.
 */
class TelegramController extends DefaultController
{

    /**
     * Lists all Telegram models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TelegramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Updates an existing Telegram model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param mixed $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id = false)
    {
        if (!$id) $model = new Telegram();
        else $model = $this->findModel($id);

        $_old_token = $model->token;
        $_old_base_url = $model->base_url;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->token != $_old_token or $model->base_url != $_old_base_url or UploadedFile::getInstance($model, 'certificate')){
                $certificate = UploadedFile::getInstance($model, 'certificate');
                Telegram::setWebhook($certificate);
            }

            return $this->renderPartial('update', [
                'model' => $model,
                'msg' => 'Save successful',
                'webHookStatus' => Telegram::getWebhookInfo(),
            ]);
        }
        return $this->renderPartial('update', [
            'model' => $model,
            'msg' => 'Save successful',
            'webHookStatus' => Telegram::getWebhookInfo(),
        ]);
    }


    /**
     * Finds the Telegram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Telegram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Telegram::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
