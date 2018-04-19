<?php

namespace app\controllers;

use app\components\SVG;
use app\models\IssueSearch;
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

        $dataProvider->query->orderBy(['id' => SORT_DESC]);

        return $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'project' => Project::findOne(['id' => $project_id])
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
        Url::remember();
        $searchModel = new IssueSearch();
        $searchModel->resolved_version_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'issue' => $this->renderPartial('@app/views/issue/index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ])
        ]);
    }
    
    public function actionRelease($id)
    {
        $model = $this->findModel($id);
        $model->status = Version::RELEASED;
        $model->finish_date = date('Y-m-d H:i');
        if ($model->save(false)) {
            $this->sendToTelegram(sprintf('User %s <b>RELEASED</b> version <b>%s</b> in project <b>%s</b>',
                Yii::$app->user->identity->username,
                $model->name,
                Project::findOne(['id' => $model->project_id])->name
            ));
        }
        return $this->redirect(Url::previous());
    }

    public function actionUnrelease($id)
    {
        $model = $this->findModel($id);
        $model->status = Version::UNRELEASED;
        $model->finish_date = null;
        if($model->save(false)) {
            $this->sendToTelegram(sprintf('User %s <b>UNRELEASED</b> version <b>%s</b> in project <b>%s</b>',
                Yii::$app->user->identity->username,
                $model->name,
                Project::findOne(['id' => $model->project_id])->name
            ));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * Creates a new Version model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project_id = null)
    {
        $model = new Version();

        $model->project_id = $project_id;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() and $model->save()) {
                $this->sendToTelegram(sprintf('User %s create new varsion %s in project: %s',
                    Yii::$app->user->identity->username,
                    $model->name,
                    Project::findOne(['id' => $model->project_id])->name
                ));
                return $this->redirect(['project/view', 'id' => $model->project_id]);
            }else{
                return $this->renderPartial('_form', [
                    'model' => $model,
                ]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
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
