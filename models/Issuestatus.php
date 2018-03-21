<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "issuestatus".
 *
 * @property int $id
 * @property string $name
 * @property int $state_id
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
            [['state_id'], 'integer'],
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
            'state_id' => 'State',
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
