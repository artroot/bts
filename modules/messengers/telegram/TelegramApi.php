<?php

namespace app\modules\messengers\telegram;

use app\modules\admin\models\Telegram;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: art
 * Date: 15.05.18
 * Time: 12:45
 */
class TelegramApi
{
    public static function sendMessage($chat_id, $message, $reply_msg_id = false, $reply_markup = false)
    {
        $data = [
            'chat_id' => $chat_id,
            'text' => $message,
            'reply_to_message_id' => $reply_msg_id,
            'parse_mode' => 'html',
        ];
        if ($reply_markup) $data['reply_markup'] = json_encode($reply_markup);
        self::send('sendMessage', $data);
    }

    public static function editMessage($chat_id, $message_id, $message, $reply_msg_id = false, $reply_markup = false)
    {
        $data = [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $message,
            'reply_to_message_id' => $reply_msg_id,
            'parse_mode' => 'html',
        ];
        if ($reply_markup) $data['reply_markup'] = json_encode($reply_markup);
        self::send('editMessageText', $data);
    }


    public static function getWebhookInfo()
    {
        $webhookInfo = self::send('getWebhookInfo');

        if (isset($webhookInfo->last_error_message)){
            return sprintf('<span class="label label-warning">%s</span>', $webhookInfo->last_error_message);
        }elseif (empty($webhookInfo->url)){
            return sprintf('<span class="label label-danger">%s</span>', 'Disable ' . @$webhookInfo->url);
        }elseif (!empty($webhookInfo->url)){
            return sprintf('<span class="label label-success">%s</span>', 'Enable');
        }else{
            return sprintf('<span class="label label-danger">%s</span>', 'Disable ' . @$webhookInfo->url);
        }
    }

    /**
     * @param UploadedFile $certificate
     * @return bool
     */
    public static function setWebhook($certificate)
    {
        $model = Telegram::find()->one();
        if (!$model) return false;

        $postfields = ['url' => Url::toRoute('/telegram', 'https') . Url::to(['/', 'token' => $model->token])];

        if ($certificate) $postfields['certificate'] = new \CurlFile(realpath($certificate->tempName),$certificate->type, 'certificate.' . $certificate->extension);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf(
            '%s%s/setWebhook',
            $model->base_url,
            $model->token
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($ch);

        return true;
    }

    public static function send($action, $params = [])
    {
        $model = Telegram::find()->one();
        if (!$model) return false;

        $url = sprintf('%s%s/%s',$model->base_url, $model->token, $action);

        if (!empty($params)) $url .= '?' . http_build_query($params);

        return json_decode(file_get_contents($url))->result;
    }
}