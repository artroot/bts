<?php

use yii\db\Migration;

/**
 * Class m180405_151759_set_username_is_uniq
 */
class m180405_151759_set_username_is_uniq extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'username', $this->string(255)->unique());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('user', 'username', $this->string(255));
    }
}
