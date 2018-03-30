<?php

namespace app\controllers;

use app\models\Issuestatus;
use app\models\Project;
use Yii;
use app\models\Issue;
use app\models\IssueSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IssueController implements the CRUD actions for Issue model.
 */
class IssueController extends DefaultController
{

    /**
     * Lists all Issue models.
     * @return mixed
     */
    public function actionIndex($project_id = false, $state = false, $version_id = false)
    {
        $searchModel = new IssueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if ($project_id !== false) $dataProvider->query->where(['project_id' => $project_id]);
            if ($version_id !== false) $dataProvider->query->where(['version_id' => $version_id]);
            if ($state !== false) $dataProvider->query->where(['in', 'issuestatus_id', ArrayHelper::map(Issuestatus::findAll(['state_id' => $state]), 'id', 'id')]);


        return $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'project' => $project_id ? Project::findOne(['id' => $project_id]) : []
        ]);
    }

    /**
     * Displays a single Issue model.
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
     * Creates a new Issue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Issue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sendToTelegram(sprintf('User %s create new issue %s in project: %s',
                Yii::$app->user->identity->username,
                $model->name,
                Project::findOne(['id' => $model->getVersion()->project_id])->name
            ));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->issuestatus_id = 2;
        $model->project_id = Project::find()->one()->id;

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionNew()
    {
        $model = new Issue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('new', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Issue model.
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
     * Deletes an existing Issue model.
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
     * Finds the Issue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Issue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Issue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
