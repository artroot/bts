<?php

use yii\db\Migration;

/**
 * Class m180406_100240_fill_default_user_admin_first_last_names
 */
class m180406_100240_fill_default_user_admin_first_last_names extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('user', ['first_name' => 'Koala', 'last_name' => 'Admin'], ['id' => 1]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180406_100240_fill_default_user_admin_first_last_names cannot be reverted.\n";
    }
}
