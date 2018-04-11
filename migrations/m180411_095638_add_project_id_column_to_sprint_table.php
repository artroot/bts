<?php

use yii\db\Migration;

/**
 * Handles adding project_id to table `sprint`.
 */
class m180411_095638_add_project_id_column_to_sprint_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('sprint', 'project_id', $this->integer()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('sprint', 'project_id');
    }
}
