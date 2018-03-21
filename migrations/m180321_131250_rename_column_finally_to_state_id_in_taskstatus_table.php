<?php

use yii\db\Migration;

/**
 * Class m180321_131250_rename_column_finally_to_state_id_in_taskstatus_table
 */
class m180321_131250_rename_column_finally_to_state_id_in_taskstatus_table extends Migration
{
    public function up()
    {
        $this->renameColumn('issuestatus', 'finally', 'state_id');
        $this->alterColumn('issuestatus', 'state_id', $this->integer(11)->notNull());
    }

    public function down()
    {
        $this->renameColumn('issuestatus', 'state_id', 'finally');
        $this->alterColumn('issuestatus', 'finally', $this->boolean()->defaultValue(0));
    }
}
