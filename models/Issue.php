<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "issue".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $create_date
 * @property string $finish_date
 * @property string $plan_date
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
 */
class Issue extends \yii\db\ActiveRecord
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
            [['create_date', 'finish_date', 'plan_date'], 'safe'],
            [['issuetype_id', 'issuepriority_id', 'issuestatus_id', 'sprint_id', 'version_id', 'resolved_version_id', 'detected_version_id', 'performer_id', 'owner_id', 'parentissue_id', 'relatedissue_id'], 'integer'],
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
            'issuetype_id' => 'Issuetype ID',
            'issuepriority_id' => 'Issuepriority ID',
            'issuestatus_id' => 'Issuestatus ID',
            'sprint_id' => 'Sprint ID',
            'version_id' => 'Version ID',
            'resolved_version_id' => 'Resolved Version ID',
            'detected_version_id' => 'Detected Version ID',
            'performer_id' => 'Performer ID',
            'owner_id' => 'Owner ID',
            'parentissue_id' => 'ParentIssue ID',
            'relatedissue_id' => 'RelatedIssue ID',
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
