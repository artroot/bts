<?php

namespace app\controllers;

use app\models\Observer;
use app\models\User;
use app\models\Users;
use app\modules\admin\models\Notifyrule;
use app\modules\admin\models\Telegram;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'view', 'update', 'delete', 'create', 'get', 'graph', 'signup', 'draft',
                        'new', 'search', 'release', 'unrelease'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['request-password-reset', 'error'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function sendToTelegram($message, $action = false, $model = false, $reply_markup = false)
    {
        if(Telegram::find()->one()->status && $action && $model) {
            $chapter = explode('\\', $model->className());
            $chapter = array_pop($chapter);
            $users_ids = [];
            switch ($chapter){
                case 'Issue':
                    $users_ids = Notifyrule::find()->where(['telegram' => true])->andWhere(['chapter' => 3])->andWhere([$action => true])
                        ->andWhere(['or',['owner' => true], ['performer' => true], ['all' => true]])->all();

                    break;
                default:
                    return false;
                    break;
            }

            /*if (Observer::find()->where(['issue_id' => $model->id])->where(['user_id' => Yii::$app->user->identity->getId()])->one()){
                $users_ids[Yii::$app->user->identity->getId()] = Yii::$app->user->identity->getId();
            }
            if ($model->owner_id == Yii::$app->user->identity->getId()) {
                $users_ids[Yii::$app->user->identity->getId()] = Yii::$app->user->identity->getId();
            }
            if ($model->performer_id == Yii::$app->user->identity->getId()) {
                $users_ids[Yii::$app->user->identity->getId()] = Yii::$app->user->identity->getId();
            }*/

            foreach ($users_ids as $rule){
                $user = Users::findOne(['id' => $rule->user_id]);
                $send = false;
                if (!$user or !$user->telegram_notify or empty($user->telegram_key)) continue;
                if(!$send && $rule->all) $send = true;
                elseif(@Observer::find()->where(['issue_id' => $model->id])->andWhere(['user_id' => $user->id])->one()->id) $send = true;
                elseif(!$send && $model->owner_id == $user->id && $rule->owner) $send = true;
                elseif(!$send && $model->performer_id == $user->id && $rule->performer) $send = true;

                if($send) {
                    Telegram::sendMessage(base64_decode($user->telegram_key), ($user->id == Yii::$app->user->identity->getId() ? 'You' : Yii::$app->user->identity->index()) . ' ' . $message, false, $reply_markup);
                }

            }


            /*foreach (Users::find()->where(['telegram_notify' => 1])->andWhere(['in', 'id', $users_ids])->all() as $users) {
                if (!empty($users->telegram_key)) {
                    Telegram::sendMessage(base64_decode($users->telegram_key), ($users->id == Yii::$app->user->identity->getId() ? 'You' : Yii::$app->user->identity->index()) . ' ' . $message);
                }
            }*/
        }
    }

}
