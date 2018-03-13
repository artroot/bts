<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $usertype_id
 * @property string $username
 * @property string $password
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Task $task
 * @property Task[] $tasks
 * @property Taskviewer[] $taskviewers
 * @property Teamusers[] $teamusers
 * @property Usertype $usertype
 */
class Users extends \yii\db\ActiveRecord
{
    public $new_password;
    public $conf_password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usertype_id', 'status'], 'integer'],
            [['password_hash', 'password_reset_token', 'auth_key', 'new_password', 'conf_password'], 'string'],
            [['status'], 'required'],
            [['new_password', 'conf_password'], 'required', 'on'=>'insert'],
            ['conf_password', 'compare', 'compareAttribute' => 'new_password', 'skipOnEmpty' => false, 'message'=>"Passwords don't match"],
            [['created_at', 'updated_at', 'telegram_key'], 'safe'],
            [['username', 'email'], 'string', 'max' => 255],
            [['usertype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usertype::className(), 'targetAttribute' => ['usertype_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usertype_id' => 'Usertype ID',
            'username' => 'Username',
            'new_password' => 'New Password',
            'conf_password' => 'Confirm Password',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'telegram_key' => 'Telegram Key',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['performer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskviewers()
    {
        return $this->hasMany(Taskviewer::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamusers()
    {
        return $this->hasMany(Teamusers::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsertype()
    {
        return $this->hasOne(Usertype::className(), ['id' => 'usertype_id']);
    }
}
