<?php

namespace app\controllers;

use app\models\Issue;
use app\models\Project;
use app\models\Version;
use Yii;
use app\models\Sprint;
use app\models\SprintSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SprintController implements the CRUD actions for Sprint model.
 */
class SprintController extends DefaultController
{

    /**
     * Lists all Sprint models.
     * @return mixed
     */
    public function actionIndex($project_id)
    {
        $searchModel = new SprintSearch();
        $searchModel->project_id = $project_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sprint model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $sprintIssues = [];

        foreach ($model->getIssues()->all() as $issue){
            $sprintIssues[$issue->getStatus()->name][] = $issue;
        }
        
        return $this->render('view', [
            'model' => $model,
            'sprintIssues' => $sprintIssues
        ]);
    }

    /**
     * Creates a new Sprint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sprint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->version_id){
                foreach (Issue::find()->where(['resolved_version_id' => $model->version_id])->all() as $issueModel){
                    $issueModel->sprint_id = $model->id;
                    $issueModel->save(false);
                }
            }

            $this->sendToTelegram(sprintf('User <b>%s</b> CREATED the <b>%s</b> in project <b>%s</b>' . "\r\n" . '<code>%s</code>' . "\r\n" . 'Link: %s',
                Yii::$app->user->identity->username,
                $model->index(),
                $model->getProject()->name,
                $model->name,
                Url::to(['/sprint/view', 'id' => $model->id], true)
            ));
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->project_id = Project::find()->one()->id;

        return $this->renderAjax('create', [
            'model' => $model,
            'action' => '/sprint/draft',
            'disableProjectAndVersion' => false
        ]);
    }

    /**
     * Creates a new Sprint model.
     * @return mixed
     */
    public function actionDraft($id = false)
    {
        $model = $id ? $this->findModel($id) : new Sprint();

        $model->load(Yii::$app->request->post());
            return $this->renderPartial('_form', [
                'model' => $model,
                'action' => '/sprint/draft' . ($id ? '?id=' . $id : ''),
                'disableProjectAndVersion' => false
            ]);
    }

    /**
     * Updates an existing Sprint model.
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

        return $this->renderAjax('update', [
            'model' => $model,
            'action' => '/sprint/draft?id=' . $id,
            'disableProjectAndVersion' => true
        ]);
    }

    /**
     * Deletes an existing Sprint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        foreach (Issue::find()->where(['sprint_id' => $id])->all() as $issueModel) {
            $issueModel->sprint_id = null;
            $issueModel->save(false);
        }

        if($this->findModel($id)->delete())

        return $this->redirect(Url::previous());
    }

    /**
     * Finds the Sprint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sprint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sprint::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
