<?php

use yii\db\Migration;

/**
 * Handles adding status to table `sprint`.
 */
class m180411_101049_add_status_column_to_sprint_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('sprint', 'status', $this->boolean()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('sprint', 'status');
    }
}
