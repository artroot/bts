<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sprint".
 *
 * @property int $id
 * @property string $name
 * @property int $version_id
 * @property string $start_date
 * @property string $finish_date
 * @property int $project_id
 * @property boolean $status
 *
 * @property Version $version
 * @property Issue[] $issues
 */
class Sprint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sprint';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version_id', 'project_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['start_date', 'finish_date'], 'string'],
            [['start_date', 'finish_date', 'project_id'], 'required'],
            [['version_id'], 'exist', 'skipOnError' => true, 'targetClass' => Version::className(), 'targetAttribute' => ['version_id' => 'id']],
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
            'version_id' => 'Version',
            'project_id' => 'Project',
            'start_date' => 'Start Date',
            'finish_date' => 'Finish Date',
            'status' => 'Status',
        ];
    }

    /**
     * @return Version
     */
    public function getVersion()
    {
        return $this->hasOne(Version::className(), ['id' => 'version_id'])->one();
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->hasOne(Version::className(), ['id' => 'version_id'])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['sprint_id' => 'id']);
    }

    /**
     * @return string
     */
    public function index()
    {
        return $this->status ? sprintf('<s>Sprint-%d</s>', $this->id) : sprintf('Sprint-%d', $this->id);
    }
}
