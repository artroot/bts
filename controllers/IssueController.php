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
        $model = new Issue();

        $model->owner_id = Yii::$app->user->identity->id;
        $model->create_date = date('Y-m-d H:i');

        if ($project_id) $model->project_id = $project_id;
        else $model->project_id = Project::find()->one()->id;

        if ($version_id) $model->resolved_version_id = $version_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->isDone()) {
                $model->finish_date = date('Y-m-d H:i:s');
                $model->save();
            }
            Log::add($model, 'create');
            $this->sendToTelegram(sprintf('User <b>%s</b> CREATED the new issue <b>%s</b> in project: <b>%s</b>' . "\r\n" . '<code>%s</code>',
                Yii::$app->user->identity->username,
                $model->name,
                Project::findOne(['id' => $model->project_id])->name,
                $model->description
            ));
            return $this->redirect(['update', 'id' => $model->id]);
        }

        $model->issuestatus_id = 2;

        return $this->render('create', [
            'model' => $model,
            'action' => '/issue/draft'
        ]);
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

            $oldModel = clone $model;

            if ($model->load(Yii::$app->request->post())) {

                if ($model->issuestatus_id != $oldModel->issuestatus_id && $oldModel->getStatus()->count_progress_from && $model->getStatus()->count_progress_to) {
                    if ($model->start_date == NULL) {

                        $model->start_date = @$model->getLastChangedStatusDate() ?: date('Y-m-d H:i');
                        return $this->renderPartial('_update_form', [
                            'model' => $model,
                            'action' => '/issue/update?id=' . $id
                        ]);
                    } else {
                        $diff = (new \DateTime())->diff((new \DateTime($model->start_date)));
                        $hours = $diff->h;
                        $hours = $hours + ($diff->days * 24);
                        $model->progress_time += $hours;
                    }
                }

                if($model->save()) {

                    if ($model->issuestatus_id !== $oldModel->issuestatus_id && $model->isDone()) {
                        $model->finish_date = date('Y-m-d H:i:s');
                        $model->save(false);
                    } elseif ($oldModel->isDone() && !$model->isDone()) {
                        $model->finish_date = '0000-00-00 00:00:00';
                        $model->save(false);
                    }

                    $changes = null;
                    foreach ($model->attributeLabels() as $key => $value) {
                        if (@$model->{$key} != @$oldModel->{$key}) {
                            $changes .= "\r\n" . 'Changed ' . @$value . "\r\n" . @$model->{$key};
                        }
                    }
                    if (!empty($changes)) {
                        Log::add($model, 'update', null, $oldModel);
                        $this->sendToTelegram(sprintf('User <b>%s</b> UPDATED issue <b>%s</b> in project: <b>%s</b>' . "\r\n" . '<code>%s</code>',
                            Yii::$app->user->identity->username,
                            $model->name,
                            Project::findOne(['id' => @$model->project_id])->name,
                            $changes
                        ));
                    }
                    $model->start_date = NULL;
                    return $this->renderPartial('_update_form', [
                        'model' => $model,
                        'action' => '/issue/update?id=' . $id
                    ]);
                }else{
                    $model->start_date = NULL;
                    return $this->renderPartial('_update_form', [
                        'model' => $model,
                        'action' => '/issue/update?id=' . $id
                    ]);
                }
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
        $this->sendToTelegram(sprintf('User <b>%s</b> DELETED the issue <b>%s</b> in project: <b>%s</b>',
            Yii::$app->user->identity->username,
            $model->name,
            @Project::findOne(['id' => $model->project_id])->name
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
