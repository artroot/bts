<?php

use yii\db\Migration;

/**
 * Class m180321_104844_rename_taskstatus_table_to_issuestatus
 */
class m180321_104844_rename_taskstatus_table_to_issuestatus extends Migration
{
    public function up()
    {
        $this->renameTable('taskstatus', 'issuestatus');
    }

    public function down()
    {
        $this->renameTable('issuestatus', 'taskstatus');
    }
}
