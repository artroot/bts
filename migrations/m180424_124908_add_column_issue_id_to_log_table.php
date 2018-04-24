<?php

use yii\db\Migration;

/**
 * Class m180424_124908_add_column_issue_id_to_log_table
 */
class m180424_124908_add_column_issue_id_to_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('log', 'issue_id', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('log', 'issue_id');
    }
}
