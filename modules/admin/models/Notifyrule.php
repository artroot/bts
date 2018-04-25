<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "notifyrule".
 *
 * @property int $id
 * @property int $user_id
 * @property int $chapter
 * @property int $mail
 * @property int $telegram
 * @property int $owner
 * @property int $performer
 * @property int $all
 * @property int $create
 * @property int $update
 * @property int $delete
 * @property int $done
 */
class Notifyrule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifyrule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'chapter', 'mail', 'telegram', 'owner', 'performer', 'create', 'update', 'delete', 'done', 'all'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chapter' => 'Chapter',
            'mail' => 'Mail',
            'telegram' => 'Telegram',
            'owner' => 'Owner',
            'performer' => 'Performer',
            'all' => 'All',
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
            'done' => 'Done',
        ];
    }
}
