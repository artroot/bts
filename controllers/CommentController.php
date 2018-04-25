<?php

namespace app\controllers;

use app\models\Project;
use app\modules\admin\models\Log;
use Yii;
use app\models\Comment;
use app\models\CommentSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends DefaultController
{

    /**
     * Lists all Comment models.
     * @return mixed
     */
    public function actionIndex($issue_id)
    {
        $model = new Comment();
        $model->issue_id = $issue_id;
        $model->user_id = Yii::$app->user->identity->getId();

        return $this->render('index', [
            'comments' => Comment::find()->where(['issue_id' => $issue_id])->orderBy(['id' => SORT_DESC])->all(),
            'createForm' => $this->renderAjax('create', [
                'model' => $model
            ])
        ]);
    }

    /**
     * Displays a single Comment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Comment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Log::add($model, 'create', $model->issue_id);
            $this->sendToTelegram(sprintf('created the new comment to issue: ' . "\r\n" . '<b>%s %s</b>' . "\r\n" . '%s' . "\r\n" . '<code>%s</code>',
                $model->getIssue()->index(),
                $model->getIssue()->name,
                Url::to(['issue/update', 'id' => $model->issue_id], true),
                $model->text
            ));
            return $this->redirect(Url::previous());
        }

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = clone $model;

        if ($model->load(Yii::$app->request->post()) && ($model->create_date = date('Y-m-d H:i:s')) && $model->save()) {
            $changes = Log::getChanges($model, $oldModel);
            Log::add($model, 'update', $model->issue_id, $oldModel);
            $this->sendToTelegram(sprintf('updated the comment in issue: ' . "\r\n" . ' <b>%s %s</b>' . "\r\n" . ' %s ' . "\r\n" . '<code>%s</code>',
                            $model->getIssue()->index(),
                            $model->getIssue()->name,
                            Url::to(['issue/update', 'id' => $model->issue_id], true) ,
                            implode("\r\n", $changes)
                        ));
            return $this->redirect(Url::previous());
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Log::add($model, 'delete', $model->issue_id);
        $this->sendToTelegram(sprintf('deleted the comment in issue: ' . "\r\n" . ' <b>%s %s</b>' . "\r\n" . '<code>%s</code>',
            $model->getIssue()->index(),
            $model->getIssue()->name,
            $model->text
        ));

        $model->delete();

        return $this->redirect(Url::previous());
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
