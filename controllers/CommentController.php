<?php

namespace app\controllers;

use app\models\Project;
use Yii;
use app\models\Comment;
use app\models\CommentSearch;
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
        /*$searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
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
            $this->sendToTelegram(sprintf('User <b>%s</b> create new comment to issue <b>%s</b> in project: <b>%s</b>' . "\r\n" . '<code>%s</code>',
                Yii::$app->user->identity->username,
                $model->getIssue()->one()->name,
                Project::findOne(['id' => $model->getIssue()->one()->project_id])->name,
                $model->text
            ));
            return $this->redirect(['/issue/update', 'id' => $model->issue_id]);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
