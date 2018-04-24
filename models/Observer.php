<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "observer".
 *
 * @property int $id
 * @property int $issue_id
 * @property int $user_id
 * @property array $observers
 *
 * @property Issue $issue
 * @property User $user
 */
class Observer extends \yii\db\ActiveRecord
{
    public $observers = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'observer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_id', 'user_id'], 'integer'],
            [['observers'], 'safe'],
            [['issue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Issue::className(), 'targetAttribute' => ['issue_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'Observer',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::className(), ['id' => 'issue_id'])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id'])->one();
    }
}
