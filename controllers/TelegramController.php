<?php

namespace app\controllers;

use app\components\TelegramBot;
use app\models\Observer;
use app\modules\admin\models\Log;
use app\modules\admin\models\Notifyrule;
use app\modules\admin\models\State;
use app\modules\admin\models\Telegram;
use app\models\Project;
use app\models\Issue;
use app\models\Users;
use app\models\Version;
use Yii;
use app\models\Comment;
use app\models\CommentSearch;
use yii\caching\Cache;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class TelegramController extends Controller
{

    public function behaviors()
    {
        $this->enableCsrfValidation = false;
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['post', 'get'],
                ],
            ],
        ];
    }

    public function actionIndex($token = false)
    {
        $model = @Telegram::find()->one();
        if ($token != @$model->token) throw new UnauthorizedHttpException('Token missed');
        if (!$model->status) throw new NotAcceptableHttpException('Service disable');


        $output = json_decode(file_get_contents('php://input'), TRUE);

        if ($output['update_id'] <= @$model->update_id) return false;

        TelegramBot::parseOutput($output);

        $model->update_id = $output['update_id'];
        $model->save(false);

        return true;
    }


    public function sendToTelegram($message, $userModel, $action = false, $model = false, $reply_markup = false)
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

            foreach ($users_ids as $rule){
                $user = Users::findOne(['id' => $rule->user_id]);
                $send = false;
                if (!$user or !$user->telegram_notify or empty($user->telegram_key)) continue;
                if(!$send && $rule->all) $send = true;
                elseif(@Observer::find()->where(['issue_id' => $model->id])->andWhere(['user_id' => $user->id])->one()->id) $send = true;
                elseif(!$send && $model->owner_id == $user->id && $rule->owner) $send = true;
                elseif(!$send && $model->performer_id == $user->id && $rule->performer) $send = true;

                if($send) {
                    Telegram::sendMessage(base64_decode($user->telegram_key), ($user->id == $userModel->id ? 'You' : $userModel->index()) . ' ' . $message, false, $reply_markup);
                }

            }
        }
    }

}
