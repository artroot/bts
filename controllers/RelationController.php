<?php

namespace app\controllers;

use app\models\Issue;
use app\modules\admin\models\Log;
use Yii;
use app\models\Relation;
use app\models\RelationSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RelationController implements the CRUD actions for Relation model.
 */
class RelationController extends DefaultController
{

    /**
     * Lists all Relation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RelationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
/**
     * Lists all Relation models.
     * @return mixed
     */
    public function actionIndexfrom()
    {
        $searchModel = new RelationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_from', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Relation model.
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
     * Creates a new Relation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Relation();

        if ($model->load(Yii::$app->request->post())){
            $relations = null;
            $from_issueModel = Issue::findOne(['id' => $model->from_issue]);
            foreach ($model->to_issues as $to_issue){
                $relation = new Relation();
                $relation->load(Yii::$app->request->post());
                $relation->to_issue = $to_issue;
                $relation->save();
                $to_issueModel = Issue::findOne(['id' => $relation->to_issue]);
                $relations .= "\r\n" . ' <b>' . $to_issueModel->index() . '</b> <b>' . $to_issueModel->name . '</b>';
                Log::add($relation, 'create', $model->from_issue);
                $this->sendToTelegram(sprintf('added associated issue: ' . "\r\n" . '<b>%s %s</b>' . "\r\n" . '%s' . "\r\n" . ' with issue:' . "\r\n" . '<b>%s %s</b>' . "\r\n" . '%s' . "\r\n" . 'With comment: ' . "\r\n" . '<code>%s</code>',
                    $from_issueModel->index(),
                    $from_issueModel->name,
                    Url::to(['issue/update', 'id' => $from_issueModel->id], true),
                    $to_issueModel->index(),
                    $to_issueModel->name,
                    Url::to(['issue/update', 'id' => $to_issueModel->id], true),
                    $model->comment
                ));
            }

            return $this->redirect(Url::previous());
        }

        return $this->redirect(Url::previous());
    }


    /**
     * Deletes an existing Relation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Log::add($model, 'delete', $model->from_issue);
        $this->sendToTelegram(sprintf('deleted the relation issue: ' . "\r\n" . '<b>%s %s</b>' . "\r\n" . '%s' . "\r\n" . ' with issue:' . "\r\n" . '<b>%s %s</b>' . "\r\n" . '%s' . "\r\n" . 'With comment: ' . "\r\n" . '<code>%s</code>',
            $model->getFrom_issue()->index(),
            $model->getFrom_issue()->name,
            Url::to(['issue/update', 'id' => $model->from_issue], true),
            $model->getTo_issue()->index(),
            $model->getTo_issue()->name,
            Url::to(['issue/update', 'id' => $model->to_issue], true),
            $model->comment
        ));

        $model->delete();

        return $this->redirect(Url::previous());
    }

    /**
     * Finds the Relation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Relation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Relation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
