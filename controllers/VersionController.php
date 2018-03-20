<?php

namespace app\controllers;

use app\components\SVG;
use app\models\TaskSearch;
use app\modules\admin\models\Telegram;
use app\models\Project;
use app\models\Users;
use Yii;
use app\models\Version;
use app\models\VersionSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VersionController implements the CRUD actions for Version model.
 */
class VersionController extends DefaultController
{

    /**
     * Lists all Version models.
     * @return mixed
     */
    public function actionIndex($project_id)
    {
        $searchModel = new VersionSearch();
        $searchModel->project_id = $project_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'project' => Project::findOne(['id' => $project_id])
        ]);
    }
    
    public function actionGraph()
    {
        $data = [
            25,
            25,
            23,
            21,
            18,
            15,
            14,
            13,
            9,
            9,
            9,
            9,
            9,
            1,
            0
        ];

        $svg = SVG::generate($data);

        return $this->render('graph', [
            'graphs' => [
                $svg->getIdeal(),
                $svg->getCoords(),
            ], 
            'scales' => $svg->getScales(),
        ]);
    }

    /**
     * Displays a single Version model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new TaskSearch();
        $searchModel->version_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'task' => $this->renderPartial('@app/views/task/index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ])
        ]);
    }

    /**
     * Creates a new Version model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project_id)
    {
        $model = new Version();

        $model->project_id = $project_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sendToTelegram(sprintf('User %s create new varsion %s in project: %s',
                Yii::$app->user->identity->username,
                $model->name,
                Project::findOne(['id' => $model->project_id])->name
                ));
            return $this->redirect(['project/view', 'id' => $model->project_id]);
        }

        return $this->renderAjax('create', [
            //'model' => $model,
            'versionForm' => $this->renderPartial('_form', [
                'model' => $model,
            ])
        ]);
    }

    /**
     * Updates an existing Version model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sendToTelegram(sprintf('User %s update version %s in project: %s',
                Yii::$app->user->identity->username,
                $model->name,
                Project::findOne(['id' => $model->project_id])->name
            ));
            return $this->redirect(['project/view', 'id' => $model->project_id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'versionForm' => $this->renderPartial('_form', [
                'model' => $model,
            ])
        ]);
    }

    /**
     * Deletes an existing Version model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Url::canonical());
    }


    
    /**
     * Finds the Version model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Version the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Version::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
