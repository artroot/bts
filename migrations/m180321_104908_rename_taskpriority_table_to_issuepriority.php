<?php

use yii\db\Migration;

/**
 * Class m180321_104908_rename_taskpriority_table_to_issuepriority
 */
class m180321_104908_rename_taskpriority_table_to_issuepriority extends Migration
{
    public function up()
    {
        $this->renameTable('taskpriority', 'issuepriority');
    }

    public function down()
    {
        $this->renameTable('issuepriority', 'taskpriority');
    }
}
