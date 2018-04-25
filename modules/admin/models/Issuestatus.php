<?php

namespace app\modules\admin\models;

use app\models\Issue;
use Yii;

/**
 * This is the model class for table "issuestatus".
 *
 * @property int $id
 * @property string $name
 * @property int $state_id
 * @property int $count_progress_from
 * @property int $count_progress_to
 *
 * @property Issue[] $issues
 */
class Issuestatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issuestatus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id'], 'required'],
            [['state_id', 'count_progress_to', 'count_progress_from'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'state_id' => 'Main State',
            'count_progress_from' => 'Count the execution time in the transition from',
            'count_progress_to' => 'Count the execution time in the transition to',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['issuestatus_id' => 'id']);
    }
}
