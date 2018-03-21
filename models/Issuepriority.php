<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "issuepriority".
 *
 * @property int $id
 * @property string $name
 * @property int $level
 *
 * @property Issue[] $issues
 */
class Issuepriority extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issuepriority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'required'],
            [['level'], 'integer'],
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
            'level' => 'Level',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['issuepriority_id' => 'id']);
    }
}
