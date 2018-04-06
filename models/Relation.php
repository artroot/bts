<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "relation".
 *
 * @property int $id
 * @property int $from_issue
 * @property int $to_issue
 * @property string $comment
 * @property array $to_issues
 */
class Relation extends \yii\db\ActiveRecord
{
    public $to_issues;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_issue', 'to_issue'], 'required'],
            [['to_issue', 'from_issue'], 'integer'],
            [['to_issues'], 'safe'],
            [['comment'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_issue' => 'From Issue',
            'to_issue' => 'To Issue',
            'comment' => 'Comment',
        ];
    }

    /**
     * @return Issue
     */
    public function getFrom()
    {
        return $this->hasOne(Issue::className(), ['from_issue' => 'id'])->one();
    }

    /**
     * @return Issue
     */
    public function getTo()
    {
        return $this->hasOne(Issue::className(), ['to_issue' => 'id'])->one();
    }
}
