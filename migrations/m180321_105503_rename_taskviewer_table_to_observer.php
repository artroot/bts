<?php

use yii\db\Migration;

/**
 * Class m180321_105503_rename_taskviewer_table_to_observer
 */
class m180321_105503_rename_taskviewer_table_to_observer extends Migration
{
    public function up()
    {
        $this->renameTable('taskviewer', 'observer');
    }

    public function down()
    {
        $this->renameTable('observer', 'taskviewer');
    }
}
