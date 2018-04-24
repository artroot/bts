<?php

namespace app\controllers;

use app\models\Users;
use app\models\UsersSearch;
use app\modules\admin\models\Log;
use Yii;
use app\models\Observer;
use app\models\ObserverSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ObserverController implements the CRUD actions for Observer model.
 */
class ObserverController extends DefaultController
{
    
    /**
     * Lists all Observer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ObserverSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Observer model.
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
     * Creates a new Observer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Observer();

        if ($model->load(Yii::$app->request->post()) && $model->observers) {
            foreach ($model->observers as $user_id){
                $observer = clone $model;
                $observer->user_id = $user_id;
                $observer->save();
                Log::add($observer, 'added', $observer->issue_id);
            }
        }
        return $this->redirect(Url::previous());
    }

    public function actionSearch()
    {
        $searchModel = new UsersSearch();

        $search['UsersSearch']['username'] = @Yii::$app->request->queryParams['UsersSearch']['username'];
        $search['UsersSearch']['first_name'] = Yii::$app->request->queryParams['UsersSearch']['username'];
        $search['UsersSearch']['last_name'] = Yii::$app->request->queryParams['UsersSearch']['username'];
        $search['_pjax'] = @Yii::$app->request->queryParams['_pjax'];

        $dataProvider = $searchModel->search($search, [
            ['NOT IN', 'id', ArrayHelper::map(Observer::find()->where(['issue_id' => @Yii::$app->request->queryParams['issue_id']])->all(),'user_id', 'user_id')]
        ]);

        return $this->renderAjax('search_users_index', [
            'dataProvider' => $dataProvider
        ]);
    }
    
    /**
     * Updates an existing Observer model.
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
     * Deletes an existing Observer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $oldModel = clone $this->findModel($id);
        $this->findModel($id)->delete();
        Log::add($oldModel, 'delete', $oldModel->issue_id);
        return $this->redirect(Url::previous());
    }

    /**
     * Finds the Observer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Observer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Observer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
