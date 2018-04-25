<?php

namespace app\models;

use Yii;

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
            //[['issue_id','user_id'], 'required'],
            [['text'], 'string'],
            //[['issue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Issue::className(), 'targetAttribute' => ['issue_id' => 'id']],
            //[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
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
}
