<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/30/2018
 * Time: 12:43 PM
 */

namespace app\components;


use app\models\Observer;
use app\models\Users;
use app\modules\admin\models\Notifyrule;
use app\modules\admin\models\Telegram;
use app\modules\messengers\Messenger;
use Yii;
use yii\caching\Cache;
use yii\db\ActiveRecord;

/**
 * Class Owl
 * @package app\components
 *
 * @property string $action
 * @property string $text
 * @property Users $owner
 * @property ActiveRecord $model
 * @property array $reply_markup
 */
class Owl
{
    private static $instance;

    const MESSENGER_DIR = __DIR__ . '/../modules/messengers';

    private $action;
    private $text;
    private $owner;
    private $model;
    private $reply_markup;

    private function __construct($params)
    {
        foreach ($params as $key => $value) $this->{$key} = $value;

        $this->sendViaTelegram();
    }

    /**
     * @param string $action
     * @param string $text
     * @param ActiveRecord $model
     * @param bool|array $reply_markup
     * @param bool|Users $owner
     * @return Owl
     */
    public static function notify($action, $model, $text, $reply_markup = false, $owner = false):Owl
    {
        if (null === static::$instance) {
            static::$instance = new static([
                'action' => $action,
                'model' => $model,
                'text' => $text,
                'reply_markup' => $reply_markup,
                'owner' => $owner ? $owner : Yii::$app->user->identity,
            ]);
        }

        return static::$instance;
    }

    private function sendViaTelegram()
    {
        if(Telegram::find()->one()->status && $this->action && $this->model) {
            $chapter = explode('\\', $this->model->className());
            $chapter = array_pop($chapter);
            switch ($chapter){
                case 'Issue':
                    $users_ids = Notifyrule::find()->where(['telegram' => true])->andWhere(['chapter' => 3])->andWhere([$this->action => true])
                        ->andWhere(['or',['owner' => true], ['performer' => true], ['all' => true]])->all();

                    break;
                default:
                    $users_ids = [];
                    break;
            }

            foreach ($users_ids as $rule){
                $user = Users::findOne(['id' => $rule->user_id]);
                $send = false;
                if (!$user or !$user->telegram_notify or empty($user->telegram_key)) continue;
                if(!$send && $rule->all) $send = true;
                elseif(@Observer::find()->where(['issue_id' => $this->model->id])->andWhere(['user_id' => $user->id])->one()->id) $send = true;
                elseif(!$send && $this->model->owner_id == $user->id && $rule->owner) $send = true;
                elseif(!$send && $this->model->performer_id == $user->id && $rule->performer) $send = true;

                if($send) {
                    Telegram::sendMessage(base64_decode($user->telegram_key), ($user->id == $this->owner->id ? 'You' : $this->owner->index()) . ' ' . $this->text, false, $this->reply_markup);
                }

            }
        }
    }


    public static function getMessengers()
    {
        if ($messengerClasses = Yii::$app->cache->get('messengerClasses') and is_array($messengerClasses) and !empty($messengerClasses)) return $messengerClasses;
        $messengerClasses = [];
        if (is_dir(self::MESSENGER_DIR) and $messengersDirs = scandir(self::MESSENGER_DIR) and !empty($messengersDirs)){
            foreach ($messengersDirs as $messengersDir){
                if ($messengersDir != '.' and $messengersDir != '..' and is_dir(self::MESSENGER_DIR . '/' . $messengersDir)
                    and $messengers = scandir(self::MESSENGER_DIR . '/' . $messengersDir) and !empty($messengers)){
                    foreach ($messengers as $messengerClass){
                        $pathToClass = self::MESSENGER_DIR . '/' . $messengersDir . '/' . $messengerClass;
                        $className = str_replace('.php', '', 'app\modules\messengers\\' . $messengersDir . '\\' . $messengerClass);
                        if ($messengerClass != '.' and $messengerClass != '..' and is_file($pathToClass) and class_exists($className)
                            and is_subclass_of($className, Messenger::class, true)){
                            $messengerClasses[] = $className;
                        }
                    }
                }
            }
        }
        Yii::$app->cache->delete('messengerClasses');
        Yii::$app->cache->add('messengerClasses', $messengerClasses, 90000);
        return $messengerClasses;
    }
    

}