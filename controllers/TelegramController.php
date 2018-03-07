<?php

namespace app\controllers;

use app\modules\admin\models\Telegram;
use app\models\Project;
use app\models\Task;
use app\models\Users;
use app\models\Version;
use Yii;
use app\models\Comment;
use app\models\CommentSearch;
use yii\filters\AccessControl;
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

        $chat_id = $output['message']['chat']['id'];
        $message = $output['message']['text'];


        if ($message == '/start'){
            $text = sprintf('<code>%s</code> Insert this key into your bug tracking system profile.', base64_encode($chat_id));
            Telegram::sendMessage($chat_id, $text);
        }elseif ($message == '/projectList'){
            $text = '';
            if (Users::findOne(['telegram_key' => base64_encode($chat_id)])){
                $i = 0;
                foreach (Project::find()->all() as $project){
                    $text .=  ++$i . '. <b>' . $project->name . ' - ' . $project->description . "</b>\r\n" . '<i>Show versions:</i> /versions' . $project->id  . "\r\n" . '--------------' . "\r\n";
                }
                Telegram::sendMessage($chat_id, $text);
            }
        }elseif (strpos($message, '/versions') !== false){
            preg_match('/versions+([0-9]+)/', $message, $res);

            $text = '';
            if (Users::findOne(['telegram_key' => base64_encode($chat_id)])){
                foreach (Version::findAll(['project_id' => $res[1]]) as $version){
                    $text .=  $version->name . "\r\n" .  'Tasks active(' . Task::find()->where(['version_id' => $version->id])->count() . ')' .  "\r\n";
                }
                Telegram::sendMessage($chat_id, $text);
            }
        }

        $model->update_id = $output['update_id'];
        $model->save(false);

        return true;
    }
}
