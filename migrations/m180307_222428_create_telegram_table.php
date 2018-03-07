<?php

use yii\db\Migration;

/**
 * Handles the creation of table `telegram`.
 */
class m180307_222428_create_telegram_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('telegram', [
            'token' => $this->string(255)->notNull() . ' primary key',
            'base_url' => $this->string(255)->notNull(),
            'update_id' => $this->integer(20)->defaultValue(0),
            'status' => $this->boolean()->defaultValue(0)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('telegram');
    }
}
