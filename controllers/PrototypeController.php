<?php

namespace app\controllers;

use Yii;
use app\models\Prototype;
use app\models\PrototypeSearch;
use app\controllers\DefaultController;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use ZipArchive;

/**
 * PrototypeController implements the CRUD actions for Prototype model.
 */
class PrototypeController extends DefaultController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Prototype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrototypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Prototype model.
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
     * Creates a new Prototype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($issue_id = false)
    {
        $model = new Prototype();

        if ($issue_id) $model->issue_id = $issue_id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->resource = UploadedFile::getInstance($model, 'resource');
            $dirName = sha1(time().$model->resource->baseName.time());
            $model->path = sprintf('/prototypes/%s', $dirName);
            mkdir(Yii::$app->basePath . '/web' . $model->path, 0777, true);
            $zip = new ZipArchive;
            $zip->open($model->resource->tempName);
            $zip->extractTo(Yii::$app->basePath . '/web' . $model->path);
            $zip->close();
            $model->save();
            return $this->renderAjax('_update_form', [
                'model' => $model,
                'action' => '/prototype/update?id=' . $model->id
            ]);
            //return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'action' => '/prototype/update?id=' . $model->id
        ]);
    }

    public function actionFilebrowser($id)
    {
        $model = $this->findModel($id);

        if (isset(Yii::$app->request->queryParams['browse_to'])){
            $model->setTree(Yii::$app->request->queryParams['browse_to']);
        }elseif (isset(Yii::$app->request->queryParams['back_to'])){
            $model->tree = explode('/', Yii::$app->request->queryParams['back_to']);
        }

        return $this->renderAjax('_update_form', [
            'model' => $model,
            'action' => '/prototype/update?id=' . $model->id
        ]);
    }


    /**
     * Deletes an existing Prototype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $path = $model->path;
        $this->findModel($id)->delete();
        Prototype::delTree(Yii::$app->basePath . '/web' . $path);
        return $this->redirect(Url::previous());
    }

    /**
     * Finds the Prototype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prototype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prototype::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
