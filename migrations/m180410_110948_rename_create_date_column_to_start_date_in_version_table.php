<?php

use yii\db\Migration;

/**
 * Class m180410_110948_rename_create_date_column_to_start_date_in_version_table
 */
class m180410_110948_rename_create_date_column_to_start_date_in_version_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('version', 'create_date', 'start_date');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('version', 'start_date', 'create_date');
    }
}
