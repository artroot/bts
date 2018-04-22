<?php

namespace app\models;

use app\modules\admin\models\Issuestatus;
use app\modules\admin\models\Log;
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
 * @property int $resolved_version_id
 * @property int $detected_version_id
 * @property int $performer_id
 * @property int $owner_id
 * @property int $project_id
 * @property int $progress_time
 *
 * @property int $start_date
 */
class Issue extends ActiveRecord
{

    public $start_date;

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
            [['create_date', 'finish_date', 'deadline', 'start_date'], 'safe'],
            [['issuetype_id', 'project_id', 'issuepriority_id', 'issuestatus_id', 'sprint_id', 'resolved_version_id', 'detected_version_id', 'performer_id', 'owner_id', 'progress_time'], 'integer'],
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
            'create_date' => 'Created',
            'finish_date' => 'Finished',
            'start_date' => 'Started at',
            'deadline' => 'Deadline',
            'issuetype_id' => 'Type',
            'issuepriority_id' => 'Priority',
            'issuestatus_id' => 'Status',
            'sprint_id' => 'Sprint',
            'resolved_version_id' => 'Resolved Version',
            'detected_version_id' => 'Detected Version',
            'performer_id' => 'Performer',
            'owner_id' => 'Owner',
            'project_id' => 'Project',
            'progress_time' => 'Spent Time',
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
     * @return Project
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id'])->one();
    }

    /**
     * @return Issuepriority
     */
    public function getPriority()
    {
        return $this->hasOne(Issuepriority::className(), ['id' => 'issuepriority_id'])->one();
    }

    /**
     * @return Issuetype
     */
    public function getType()
    {
        return $this->hasOne(Issuetype::className(), ['id' => 'issuetype_id'])->one();
    }

    /**
     * @return Users
     */
    public function getOwner()
    {
        return $this->hasOne(Users::className(), ['id' => 'owner_id'])->one();
    }

    /**
     * @return Users
     */
    public function getPerformer()
    {
        return $this->hasOne(Users::className(), ['id' => 'performer_id'])->one();
    }

    /**
     * @return Issuestatus
     */
    public function getStatus()
    {
        return $this->hasOne(Issuestatus::className(), ['id' => 'issuestatus_id'])->one();
    }

    public function getIssuestatus()
    {
        return $this->getStatus();
    }

    /**
     * @return Issuepriority
     */
    public function getIssuepriority()
    {
        return $this->getPriority();
    }

    public function getIssuetype()
    {
        return $this->getType();
    }

    public function isDone()
    {
        return Issuestatus::findOne(['id' => $this->issuestatus_id])->state_id == State::DONE;
    }

    public function index()
    {
        return sprintf('#%s-%s', @$this->getProject()->name, @$this->id);
    }

    /**
     * @return ActiveRecord
     */
    public function getAssociateWith()
    {
        return Relation::find()->where(['from_issue' => $this->id]);
    }

    /**
     * @return ActiveRecord
     */
    public function getRelatedFor()
    {
        return Relation::find()->where(['to_issue' => $this->id]);
    }

    /**
     * @return ActiveRecord
     */
    public function getPrototypes()
    {
        return Prototype::find()->where(['issue_id' => $this->id]);
    }

    /**
     * @return ActiveRecord
     */
    public function getAttachments()
    {
        return Attachment::find()->where(['issue_id' => $this->id]);
    }

    public function getResolved_version()
    {
        return $this->hasOne(Version::className(), ['id' => 'resolved_version_id'])->one();
    }

    public function getDetected_version()
    {
        return $this->hasOne(Version::className(), ['id' => 'detected_version_id'])->one();
    }

    public function getSprint()
    {
        return $this->hasOne(Sprint::className(), ['id' => 'sprint_id'])->one();
    }

    public function getLastChangedStatusDate()
    {
        $log = Log::find()
            ->where(['model' => $this->className()])
            ->where(['model_id' => $this->id])
            ->where(['like', 'data_new', '%issuestatus_id%', false])
            ->orderBy(['id' => SORT_DESC])->limit(1)->one();
        return @$log->date;
    }

    public function getProgressTime()
    {
        if ($this->progress_time) {
            $diff = (new \DateTime())->diff((new \DateTime())->modify('-' . $this->progress_time . ' hour'));
            $msg = null;
            if ($diff->format('%y')) $msg .= $diff->format('%y') . ' Years ';
            if ($diff->format('%m')) $msg .= $diff->format('%m') . ' Months ';
            if ($diff->format('%d')) $msg .= $diff->format('%d') . ' Days ';
            if ($diff->format('%h')) $msg .= $diff->format('%h') . ' Hour ';
        }else{
            $msg = '0 Days';
        }

        return $msg;
    }
}
