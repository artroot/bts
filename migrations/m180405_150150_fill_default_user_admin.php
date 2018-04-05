<?php

use yii\db\Migration;

/**
 * Class m180405_150150_fill_default_user_admin
 */
class m180405_150150_fill_default_user_admin extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('user', [
            'usertype_id' => 1,
            'username' => 'admin',
            'password_hash' => '$2y$13$8TnW/rJotk9323E.6SaCT.LjnGJoZzpL7luJR4FgBSfcjCiCjvwqK',
            'auth_key' => 'mKSGw-3J0vYaH_AhS1KF7jSlFA0eoDTT',
            'status' => 10,
            'telegram_notify' => 0,
            'mail_notify' => 0,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('user', ['username' => 'admin']);
    }
}
