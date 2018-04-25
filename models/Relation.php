<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

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
     * @return ActiveRecord
     */
    public function getFrom_issue()
    {
        return @$this->hasOne(Issue::className(), ['id' => 'from_issue'])->one();
    }

    /**
     * @return ActiveRecord
     */
    public function getTo_issue()
    {
        return @$this->hasOne(Issue::className(), ['id' => 'to_issue'])->one();
    }
}
