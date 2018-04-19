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
        return $this->hasOne(Project::className(), ['id' => 'project_id'])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['sprint_id' => 'id']);
    }

    public function getProgress()
    {
        do{
            if (!isset($date)) $date = (new \DateTime($this->start_date));

            $progress[] = $this->getIssues()->where(['finish_date' => NULL])->orWhere(['>', 'finish_date', $date->modify('+1 day')->format('Y-m-d 01:00:00')])->count();

        }while($date->format('Y-m-d') <= (new \DateTime())->format('Y-m-d'));

        return $progress;
    }

    public function getSprintCountDays()
    {
        $dateSatrt = new \DateTime($this->start_date);
        $dateFinish = new \DateTime($this->finish_date);

        return $dateFinish->diff($dateSatrt)->format('%a');
    }

    public function getCompleteProgressPercent()
    {
        return $this->getIssues()->count() > 0 ? ($this->getIssues()->where(['!=', 'finish_date', ''])->count())*100/($this->getIssues()->count()) : 0;
    }

    public function getDaysRemaining()
    {
        return (new \DateTime())->diff((new \DateTime($this->finish_date)))->format('%R%a');
    }

    public function getState()
    {
        if ($this->getDaysRemaining() == 0 && (new \DateTime($this->finish_date))->format('Y-m-d H:i') > date('Y-m-d H:i')){
            return sprintf('deadline day');
        }elseif($this->getDaysRemaining() <= 0){
            if ((new \DateTime($this->finish_date))->format('Y-m-d H:i'))
                return sprintf('overdue today at %s', (new \DateTime($this->finish_date))->format('H:i'));
            else
                return sprintf('overdue %d days ago', intval($this->getDaysRemaining()));
        }else{
            return sprintf('%d days remaining', intval($this->getDaysRemaining()));
        }
    }

    /**
     * @return string
     */
    public function index()
    {
        return (($this->status or date('Y-m-d H:i') > (new \DateTime($this->finish_date))->format('Y-m-d H:i')) ? sprintf('<s>Sprint-%d</s>', $this->id) : sprintf('Sprint-%d', $this->id));
    }
}
