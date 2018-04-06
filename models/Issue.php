<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "issue".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $create_date
 * @property string $finish_date
 * @property string $deadline
 * @property int $issuetype_id
 * @property int $issuepriority_id
 * @property int $issuestatus_id
 * @property int $sprint_id
 * @property int $version_id
 * @property int $resolved_version_id
 * @property int $detected_version_id
 * @property int $performer_id
 * @property int $owner_id
 * @property int $parentissue_id
 * @property int $relatedissue_id
 * @property int $project_id
 */
class Issue extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['create_date', 'finish_date', 'deadline'], 'safe'],
            [['issuetype_id', 'project_id', 'issuepriority_id', 'issuestatus_id', 'sprint_id', 'version_id', 'resolved_version_id', 'detected_version_id', 'performer_id', 'owner_id', 'parentissue_id', 'relatedissue_id'], 'integer'],
            [['owner_id', 'project_id'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Subject',
            'description' => 'Description',
            'create_date' => 'Create Date',
            'finish_date' => 'Finish Date',
            'deadline' => 'Deadline',
            'issuetype_id' => 'Type',
            'issuepriority_id' => 'Priority',
            'issuestatus_id' => 'State',
            'sprint_id' => 'Sprint',
            'version_id' => 'Version',
            'resolved_version_id' => 'Resolved Version',
            'detected_version_id' => 'Detected Version',
            'performer_id' => 'Performer',
            'owner_id' => 'Owner ID',
            'parentissue_id' => 'ParentIssue ID',
            'relatedissue_id' => 'RelatedIssue ID',
            'project_id' => 'Project',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersion()
    {
        return $this->hasOne(Version::className(), ['id' => 'resolved_version_id']);
    }

    /**
     * @param array $condition
     * @return Query
     */
    public static function getDone($condition):Query
    {
        return (new self())->find()->where($condition)->andWhere(['in', 'issuestatus_id', ArrayHelper::map(Issuestatus::findAll(['state_id' => State::getState(State::DONE)->id]), 'id', 'id')]);
    }

    /**
     * @param array $condition
     * @return Query
     */
    public static function getTodo($condition):Query
    {
        return (new self())->find()->where($condition)->andWhere(['in', 'issuestatus_id', ArrayHelper::map(Issuestatus::findAll(['state_id' => State::getState(State::TODO)->id]), 'id', 'id')]);
    }

    /**
     * @param array $condition
     * @return Query
     */
    public static function getInProgress($condition):Query
    {
        return (new self())->find()->where($condition)->andWhere(['in', 'issuestatus_id', ArrayHelper::map(Issuestatus::findAll(['state_id' => State::getState(State::IN_PROGRESS)->id]), 'id', 'id')]);
    }

    /**
     * @return Issuepriority
     */
    public function getPriority()
    {
        return $this->hasOne(Issuepriority::className(), ['id' => 'issuepriority_id'])->one();
    }
}
