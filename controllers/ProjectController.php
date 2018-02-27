<?php

namespace app\controllers;

use app\controllers\DefaultController;
use app\models\Version;
use app\models\VersionSearch;
use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\base\View;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\ViewAction;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends DefaultController
{

    public function actionGet($id)
    {
        \Yii::$app->response->format = Response::FORMAT_RAW;
        \Yii::$app->response->headers->add('content-type','image/png');
        \Yii::$app->response->data = base64_decode($this->findModel($id)->logo);
        return \Yii::$app->response;
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $action = 'versions')
    {
        switch ($action){
            case 'versions':
                $searchModel = new VersionSearch();
                $searchModel->project_id = $id;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                $data = \Yii::$app->view->renderFile('@app/views/version/index.php', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'project' => Project::findOne(['id' => $id])
                ]);
                break;
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'data' => $data
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($this->handlePostSave($model) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->handlePostSave($model) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Project model.
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

    protected function handlePostSave(Project $model)
    {
        if ($model->load(Yii::$app->request->post())) {
            if (UploadedFile::getInstance($model, 'logo')) {
                $model->logo = base64_encode(file_get_contents(UploadedFile::getInstance($model, 'logo')->tempName));
            }else{
                $model->logo = $this->findModel($model->id)->logo;
            }
            return true;
        }
        return false;
    }


    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
