<?php

use yii\db\Migration;

/**
 * Class m180420_103432_add_columns_count_progress_from_and_to_in_issuestatus_table
 */
class m180420_103432_add_columns_count_progress_from_and_to_in_issuestatus_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('issuestatus', 'count_progress_from', $this->boolean());
        $this->addColumn('issuestatus', 'count_progress_to', $this->boolean());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('issuestatus', 'count_progress_from');
        $this->dropColumn('issuestatus', 'count_progress_to');
    }

}
