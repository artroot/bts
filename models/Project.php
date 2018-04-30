<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property int $team_id
 * @property int $description
 * @property string $logo
 */
class Project extends \yii\db\ActiveRecord
{
    public $logoFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['logoFile'], 'file', 'extensions' => 'png, jpg'],
            [['name'], 'required']
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
            'team_id' => 'Team ID',
            'description' => 'Description',
            'logo' => 'Logo',
        ];
    }
}
