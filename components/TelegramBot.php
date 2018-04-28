<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 4/28/2018
 * Time: 1:15 PM
 *
 * @pro
 */

namespace app\components;

use app\models\Project;
use app\models\Users;
use app\modules\admin\models\Telegram;
use Yii;

/**
 * Class TelegramBot
 * @package app\components
 *
 * @property int $update_id
 * @property string $callback_data
 * @property int $chat_id
 * @property string $text
 * @property string $out_text
 * @property int $message_id
 * @property array $cached_data
 * @property Users|null $user
 */
class TelegramBot
{
    public $update_id;
    public $callback_data;
    public $chat_id;
    public $text;
    public $message_id;
    public $cached_data;
    public $user;

    private $out_text = ' ';
    /**
     * @var TelegramBot
     */
    private static $instance;

    private function __construct($output)
    {
        $this->setAttributes($output);
        if($this->callback_data) $this->parseCallback();
        else $this->parseCommand();
    }

    public static function parseOutput($output):TelegramBot
    {
        if (null === static::$instance) {
            static::$instance = new static($output);
        }

        return static::$instance;
    }

    private function setAttributes($output)
    {
        $this->update_id = $output['update_id'] ?: null;
        $this->callback_data = @$output['callback_query']['data'] ?: null;
        $this->chat_id = $this->callback_data ? @$output['callback_query']['message']['chat']['id'] : @$output['message']['chat']['id'];
        $this->text = $this->callback_data ? @$output['callback_query']['message']['text'] : @$output['message']['text'];
        $this->message_id = $this->callback_data ? @$output['callback_query']['message']['message_id'] : @$output['message']['message_id'];

        $this->user = Users::find()->where(['telegram_key' => base64_encode($this->chat_id),'telegram_notify' => true])->one() ?: null;
        $this->cached_data = Yii::$app->cache->get('telegram_action_user_' . $this->user->id);
    }

    private function parseCallback()
    {

    }

    private function parseCommand()
    {
        switch ($this->text){
            case '/start':
                $this->out_text = sprintf('<code>%s</code> Insert this key into your bug tracking system profile.', base64_encode($this->chat_id));
                $this->sendReplyMessage();
                break;
            case '/help':
                if(!$this->user) return false;

                $this->out_text = implode("\r\n", [
                    'Get conjugation key - /start',
                    'Show all projects list - /projects'
                ]);
                $this->sendReplyMessage();
                break;
            case '/projects':
                if(!$this->user) return false;

                $this->out_text = 'Choose a project from the list below:';

                $items = [];
                foreach (Project::find()->all() as $project) $items[sprintf('p_%d_sw', @$project->id)] = @$project->name;

                $this->sendReplyMessage($this->inlineKeyboard($items));
                break;
        }
    }

    private function inlineKeyboard($items = [], $inline = false)
    {
        $reply_markup = $btns = [];
        foreach ($items as $callback_data => $text){
            if ($inline)
                $btns[] = ['text' => $text, 'callback_data' => $callback_data];
            else
                $btns[][] = ['text' => $text, 'callback_data' => $callback_data];
        }
        if ($inline)
            $reply_markup['inline_keyboard'][] = $btns;
        else
            $reply_markup['inline_keyboard'] = $btns;

        return $reply_markup;
    }

    private function sendReplyMessage($reply_markup = false)
    {
        if ($reply_markup)
            Telegram::sendMessage($this->chat_id, $this->out_text, $this->message_id, $reply_markup);
        else
            Telegram::sendMessage($this->chat_id, $this->out_text, $this->message_id);
    }

    private function editMessage()
    {

    }

}
