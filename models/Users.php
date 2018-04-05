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
 * @property string $telegram_key
 * @property string $telegram_notify
 * @property string $mail_notify
 * @property string $first_name
 * @property string $last_name
 *
 * @property Issue $issue
 * @property Issue[] $issues
 * @property Observer[] $observers
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
            [['usertype_id', 'status', 'telegram_notify', 'mail_notify'], 'integer'],
            [['password_hash', 'password_reset_token', 'auth_key', 'new_password', 'conf_password', 'first_name', 'last_name'], 'string'],
            [['status'], 'required'],
            [['new_password', 'conf_password'], 'required', 'on'=>'insert'],
            ['conf_password', 'compare', 'compareAttribute' => 'new_password', 'skipOnEmpty' => false, 'message'=>"Passwords don't match"],
            [['telegram_key'], 'safe'],
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
            'usertype_id' => 'Group',
            'username' => 'Login',
            'new_password' => 'New Password',
            'conf_password' => 'Confirm Password',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'status' => 'Status',
            'telegram_key' => 'Telegram Key',
            'telegram_notify' => 'Telegram Notify Support',
            'mail_notify' => 'Mail Notify Support',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::className(), ['performer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObservers()
    {
        return $this->hasMany(Observer::className(), ['user_id' => 'id']);
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
