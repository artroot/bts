<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attachment".
 *
 * @property int $id
 * @property int $issue_id
 * @property string $file
 * @property string $type
 *
 * @property Issue $issue
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_id'], 'integer'],
            [['file', 'type'], 'string'],
            [['issue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Issue::className(), 'targetAttribute' => ['issue_id' => 'id']],
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
            'file' => 'File',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::className(), ['id' => 'issue_id']);
    }
}
