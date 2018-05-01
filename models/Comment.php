<?php

namespace app\models;

use app\components\Owl;
use app\components\TelegramBot;
use app\modules\admin\models\Log;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $issue_id
 * @property string $text
 * @property string $user_id
 * @property string $create_date
 *
 * @property Issue $issue
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_id','user_id'], 'integer'],
            [['text', 'create_date'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'issue_id' => 'Issue',
            'text' => 'Text',
            'user_id' => 'User',
            'create_date' => 'Created',
        ];
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::className(), ['id' => 'issue_id'])->one();
    }
    /**
     * @return Users
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id'])->one();
    }

    /**
     * @param bool $owner
     * @param bool|array $data
     * @return bool|self
     */
    public static function create($owner = false, $data = false)
    {
        $model = new Comment();
        if (is_array($data)){
            $model->setAttributes($data);
        }else{
            if (!$model->load(Yii::$app->request->post())) return false;
        }
        
        if (!$model->save()) return false;

        Log::add($model, 'create', $model->issue_id, false, $owner);

        $reply_markup = TelegramBot::inlineKeyboard([
            'i_c_add_' . $model->issue_id => 'Add Comment'
        ]);
        $text = sprintf('created the new comment to issue: ' . "\r\n" . '<b>%s %s</b>' . "\r\n" . '%s' . "\r\n" . '<code>%s</code>',
            $model->getIssue()->index(),
            $model->getIssue()->name,
            Url::to(['issue/update', 'id' => $model->issue_id], true),
            $model->text
        );

        Owl::notify('create', $model->getIssue(), $text, $reply_markup, $owner);
        return $model;
    }

    /**
     * @param bool $owner
     * @param bool|array $data
     * @return bool|self
     */
    public function updateModel($owner = false, $data = false)
    {
        $oldModel = clone $this;

        if ($data){
            $this->setAttributes($data);
        }else{
            if (!$this->load(Yii::$app->request->post())) return false;
        }

        $this->create_date = date('Y-m-d H:i:s');

        if (!$this->save()) return false;

        $changes = Log::getChanges($this, $oldModel);
        Log::add($this, 'update', $this->issue_id, $oldModel);

        $reply_markup = TelegramBot::inlineKeyboard([
            'i_c_add_' . $this->issue_id => 'Add Comment'
        ]);
        $text = sprintf('updated the comment in issue: ' . "\r\n" . ' <b>%s %s</b>' . "\r\n" . ' %s ' . "\r\n" . '<code>%s</code>',
            $this->getIssue()->index(),
            $this->getIssue()->name,
            Url::to(['issue/update', 'id' => $this->issue_id], true) ,
            implode("\r\n", $changes)
        );

        Owl::notify('update', $this->getIssue(), $text, $reply_markup, $owner);
        return $this;
    }
}
