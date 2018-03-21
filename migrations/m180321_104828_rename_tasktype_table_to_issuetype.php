<?php

use yii\db\Migration;

/**
 * Class m180321_104828_rename_tasktype_table_to_issuetype
 */
class m180321_104828_rename_tasktype_table_to_issuetype extends Migration
{
    public function up()
    {
        $this->renameTable('tasktype', 'issuetype');
    }

    public function down()
    {
        $this->renameTable('issuetype', 'tasktype');
    }
}
