<?php

namespace app\controllers;

use app\models\Users;
use app\modules\admin\models\Telegram;
use Yii;
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

    public function sendToTelegram($message)
    {
        if(Telegram::find()->one()->status) {
            foreach (Users::find()->where(['telegram_notify' => 1])->all() as $users) {
                if (!empty($users->telegram_key)) {
                    Telegram::sendMessage(base64_decode($users->telegram_key), ($users->id == Yii::$app->user->identity->getId() ? 'You' : Yii::$app->user->identity->index()) . ' ' . $message);
                }
            }
        }
    }

}
