<?php

namespace app\models;

use app\components\Owl;
use app\components\TelegramBot;
use app\modules\admin\models\Issuestatus;
use app\modules\admin\models\Log;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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
            [['owner_id', 'project_id', 'resolved_version_id', 'name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
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
     * @param bool|User $owner
     * @param bool|array $data
     * @return bool|self
     */
    public static function create($project_id = false, $version_id = false, $owner = false, $data = false)
    {
        $model = new self();

        $model->owner_id = $owner ? $owner->id: Yii::$app->user->identity->getId();
        $model->create_date = date('Y-m-d H:i:s');

        if ($project_id) $model->project_id = $project_id;
        else $model->project_id = Project::find()->one()->id;

        if ($version_id) $model->resolved_version_id = $version_id;

        if (is_array($data)){
            $model->setAttributes($data);
        }else{
            if (!$model->load(Yii::$app->request->post())) return $model;
        }

        if ($model->isDone()) $model->finish_date = date('Y-m-d H:i:s');

        if (!$model->save()) return false;

        Log::add($model, 'create', false, false, $owner);
        $changes = Log::getChanges($model);

        $reply_markup = TelegramBot::inlineKeyboard([
            'i_ed_' . $model->id => 'Edit',
            'i_c_add_' . $model->id => 'Add Comment',
        ], true);
        $text = sprintf('created the new issue: ' . "\r\n" . ' <b>%s %s</b>' . "\r\n" . ' %s ' . "\r\n" . '<code>%s</code>',
            $model->index(),
            $model->name,
            Url::to(['issue/update', 'id' => $model->id], true),
            implode("\r\n", $changes)
        );

        Owl::notify('create', $model, $text, $reply_markup, $owner);
        return $model;
    }

    /**
     * @param bool $owner
     * @param bool|array $data
     * @return bool|self
     */
    public function updateModel($owner = false, $data = false)
    {
        $oldModel = clone $this;

        if (is_array($data)){
            $this->setAttributes($data);
        }else{
            if (!$this->load(Yii::$app->request->post())) return false;
        }

        if (!$data and $this->issuestatus_id != $oldModel->issuestatus_id && $oldModel->getStatus()->count_progress_from && $this->getStatus()->count_progress_to) {
            if ($this->start_date == NULL) {

                $this->start_date = @$this->getLastChangedStatusDate() ?: date('Y-m-d H:i');
                return $this;
            } else {
                $diff = (new \DateTime())->diff((new \DateTime($this->start_date)));
                $hours = $diff->h;
                $hours = $hours + ($diff->days * 24);
                $this->progress_time += $hours;
            }
        }

        if ($this->issuestatus_id !== $oldModel->issuestatus_id && $this->isDone()) {
            $this->finish_date = date('Y-m-d H:i:s');
            $this->save(false);
        } elseif ($oldModel->isDone() && !$this->isDone()) {
            $this->finish_date = '0000-00-00 00:00:00';
            $this->save(false);
        }

        if (!$this->save()) return false;

        $changes = Log::getChanges($this, $oldModel);

        if (!empty($changes)) {
            $reply_markup = TelegramBot::inlineKeyboard([
                'i_ed_' . $this->id => 'Edit',
                'i_c_add_' . $this->id => 'Add Comment',
            ], true);

            Log::add($this, 'update', $this->id, $oldModel, $owner);
            $text = sprintf('updated the issue: ' . "\r\n" . ' <b>%s %s</b>' . "\r\n" . ' %s ' . "\r\n" . '<code>%s</code>',
                $this->index(),
                $this->name,
                Url::to(['issue/update', 'id' => $this->id], true) ,
                implode("\r\n", $changes)
            );

            Owl::notify('update', $this, $text, $reply_markup, $owner);

            $this->start_date = NULL;
        }
        return $this;
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

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['issue_id' => 'id'])->all();
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
        return @$log->date ? (new \DateTime($log->date))->format('Y-m-d H:i') : false;
    }

    public function getProgressTime()
    {
        if ($this->progress_time) {
            $diff = (new \DateTime())->diff((new \DateTime())->modify('-' . $this->progress_time . ' hour'));
            $msg = null;
            if ($diff->format('%y')) $msg .= $diff->format('%y') . ' Year ';
            if ($diff->format('%m')) $msg .= $diff->format('%m') . ' Month ';
            if ($diff->format('%d')) $msg .= $diff->format('%d') . ' Day ';
            if ($diff->format('%h')) $msg .= $diff->format('%h') . ' Hour ';
        }else{
            $msg = '0 Days';
        }

        return $msg;
    }

    public function getProgress_time()
    {
        return $this->getProgressTime();
    }

    public function getObservers()
    {
        return Observer::findAll(['issue_id' => $this->id]);
    }

    /**
     * @param bool $telegram
     * @return array
     */
    public function getAttributesLabelValues($telegram = false)
    {
        $attr = [];
        foreach ($this->attributeLabels() as $key => $label) {
            $value = false;
            $fc = 'get' . ucfirst(str_replace('_id', '', $key));
            if (isset($this->{$key})) {
                if (method_exists($this, $fc))
                    $objectNew = $this->$fc();
                if(@$objectNew instanceof ActiveRecord && isset($objectNew->name))
                    $value = $objectNew->name;
                elseif(@$objectNew instanceof ActiveRecord && method_exists($objectNew, 'index'))
                    $value = $objectNew->index();
                else
                    $value = $this->{$key};
            }
            if (@$value) {
                if ($telegram)
                    $attr[] = sprintf('<b>%s</b>: %s', $label, $value);
                else
                    $attr[$label] = $value;
            }
        }
        return $attr;
    }

}
