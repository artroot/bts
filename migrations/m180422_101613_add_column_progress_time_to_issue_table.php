<?php

use yii\db\Migration;

/**
 * Class m180422_101613_add_column_progress_time_to_issue_table
 */
class m180422_101613_add_column_progress_time_to_issue_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('issue', 'progress_time', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('issue', 'progress_time');
    }
}
