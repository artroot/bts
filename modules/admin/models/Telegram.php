<?php

namespace app\modules\admin\models;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "telegram".
 *
 * @property string $token
 * @property string $base_url
 * @property string $update_id
 * @property int $status
 */
class Telegram extends \yii\db\ActiveRecord
{
    public $certificate;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telegram';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token', 'base_url', 'status'], 'required'],
            [['update_id', 'status'], 'integer'],
            [['token', 'base_url'], 'string', 'max' => 255],
            [['token'], 'unique'],
            [['certificate'], 'file']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => 'Bot API Token',
            'base_url' => 'Base Url',
            'update_id' => 'Update ID',
            'status' => 'Telegram Bot notification support',
            'certificate' => 'SSL Certificate'
        ];
    }

    public static function sendMessage($chat_id, $message)
    {
        self::send('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'html',
        ]);
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
        $model = self::find()->one();
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
        $model = self::find()->one();
        if (!$model) return false;

        $url = sprintf('%s%s/%s',$model->base_url, $model->token, $action);

        if (!empty($params)) $url .= '?' . http_build_query($params);

        return json_decode(file_get_contents($url))->result;
    }

}
