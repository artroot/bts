<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $create_date
 * @property string $finish_date
 * @property string $plan_date
 * @property int $tasktype_id
 * @property int $taskpriority_id
 * @property int $taskstatus_id
 * @property int $sprint_id
 * @property int $version_id
 * @property int $resolved_version_id
 * @property int $detected_version_id
 * @property int $performer_id
 * @property int $owner_id
 * @property int $parenttask_id
 * @property int $relatedtask_id
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['create_date', 'finish_date', 'plan_date'], 'safe'],
            [['tasktype_id', 'taskpriority_id', 'taskstatus_id', 'sprint_id', 'version_id', 'resolved_version_id', 'detected_version_id', 'performer_id', 'owner_id', 'parenttask_id', 'relatedtask_id'], 'integer'],
            [['owner_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            /*[['performer_id'], 'unique'],*/
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
            'description' => 'Description',
            'create_date' => 'Create Date',
            'finish_date' => 'Finish Date',
            'plan_date' => 'Plan Date',
            'tasktype_id' => 'Tasktype ID',
            'taskpriority_id' => 'Taskpriority ID',
            'taskstatus_id' => 'Taskstatus ID',
            'sprint_id' => 'Sprint ID',
            'version_id' => 'Version ID',
            'resolved_version_id' => 'Resolved Version ID',
            'detected_version_id' => 'Detected Version ID',
            'performer_id' => 'Performer ID',
            'owner_id' => 'Owner ID',
            'parenttask_id' => 'Parenttask ID',
            'relatedtask_id' => 'Relatedtask ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersion()
    {
        return $this->hasOne(Version::className(), ['id' => 'version_id']);
    }
}
