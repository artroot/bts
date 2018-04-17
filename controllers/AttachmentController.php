<?php

namespace app\controllers;

use Yii;
use app\models\Attachment;
use app\models\AttachmentSearch;
use yii\web\Response;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AttachmentController implements the CRUD actions for Attachment model.
 */
class AttachmentController extends DefaultController
{

    public function actionGet($id)
    {
        $model = $this->findModel($id);
        \Yii::$app->response->format = Response::FORMAT_RAW;
        \Yii::$app->response->headers->add('content-type',$model->type);
        \Yii::$app->response->data = base64_decode($model->file);
        return \Yii::$app->response;
    }

    /**
     * Creates a new Attachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Attachment();

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');
            if ($file) {
                $model->file = base64_encode(file_get_contents($file->tempName));
                $model->type = $file->type;
                $model->base_name = $file->baseName . '.' . $file->extension;
            }
            $model->save();
            $this->sendToTelegram(sprintf('User <b>%s</b> UPLOADED the new file to issue <b>%s</b>' . "\r\n" . '%s',
                Yii::$app->user->identity->username,
                $model->getIssue()->one()->name,
                Url::to(['/issue/update', 'id' => $model->getIssue()->one()->id], true)
            ));
            return $this->redirect(Url::previous());
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Attachment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Url::previous());
    }

    /**
     * Finds the Attachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attachment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
