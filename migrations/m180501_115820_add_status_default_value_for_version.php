<?php

use yii\db\Migration;

/**
 * Class m180501_115820_add_status_default_value_for_version
 */
class m180501_115820_add_status_default_value_for_version extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('version', 'status', $this->boolean()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('version', 'status', $this->boolean());
    }
}
