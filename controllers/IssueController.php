<?php

namespace app\controllers;

use app\modules\admin\models\Issuestatus;
use app\models\Project;
use app\models\Relation;
use app\models\State;
use app\modules\admin\models\Log;
use Yii;
use app\models\Issue;
use app\models\IssueSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
            //if ($project_id !== false) $dataProvider->query->andWhere(['project_id' => $project_id]);
            if ($version_id !== false) $dataProvider->query->andWhere(['resolved_version_id' => $version_id]);
            if ($project_id !== false) $dataProvider->query->andWhere(['project_id' => $project_id]);
            if ($state !== false) $dataProvider->query->andWhere(['in', 'issuestatus_id', ArrayHelper::map(Issuestatus::findAll(['state_id' => $state]), 'id', 'id')]);

        $dataProvider->query->orderBy(['id' => SORT_DESC]);

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
    public function actionCreate($project_id = false, $version_id = false)
    {
        if(!Yii::$app->request->post()){
            $model = new Issue();

            if ($project_id) $model->project_id = $project_id;
            else $model->project_id = Project::find()->one()->id;

            if ($version_id) $model->resolved_version_id = $version_id;
        }elseif ($model = Issue::create() and $model and !$model->errors) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $model->owner_id = Yii::$app->user->identity->getId();
        $model->issuestatus_id = 2;

        if (Yii::$app->request->post()){
            return $this->renderPartial('_form', [
                'model' => $model,
                'action' => '/issue/draft'
            ]);
        }else {
            return $this->render('create', [
                'model' => $model,
                'action' => '/issue/draft'
            ]);
        }
    }

    /**
     * Creates a new Issue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDraft()
    {
        $model = new Issue();

        if ($model->load(Yii::$app->request->post())) {
            return $this->renderPartial('_form', [
                'model' => $model,
                'action' => '/issue/draft'
            ]);
        }
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
        Url::remember();

        $model = $this->findModel($id);

        if($model->updateModel() or Yii::$app->request->isPjax){
            return $this->renderPartial('_update_form', [
                'model' => $model,
                'action' => '/issue/update?id=' . $id
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'action' => '/issue/update?id=' . $id
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
        $model = $this->findModel($id);
        $redirectUrl = Url::to(['version/view', 'id' => $model->resolved_version_id]);

        $this->sendToTelegram(sprintf('deleted the issue: ' . "\r\n" . ' <b>%s %s</b>',
            $model->index(),
            $model->name
        ));
        
        $model->delete();

        return $this->redirect($redirectUrl);
    }

    public function actionSearch()
    {
        //if (isset(Yii::$app->request->post()['search'])) {
            $searchModel = new IssueSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [
                ['!=', 'id', @Yii::$app->request->queryParams['issue_id']],
                [
                    'NOT IN', 'id', ArrayHelper::map(Relation::find()->where(['from_issue' => @Yii::$app->request->queryParams['issue_id']])->all(),'to_issue', 'to_issue')
                ],
                [
                'in', 'issuestatus_id', ArrayHelper::map(Issuestatus::find()->where(['!=', 'state_id', State::DONE])->all(),'id', 'id')
                ]
            ]);

            return $this->renderPartial('search_relation_index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
        //}
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
