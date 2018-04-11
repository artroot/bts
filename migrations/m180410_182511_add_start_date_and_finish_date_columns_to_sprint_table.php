<?php

use yii\db\Migration;

/**
 * Handles adding start_date_and_finish_date to table `sprint`.
 */
class m180410_182511_add_start_date_and_finish_date_columns_to_sprint_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('sprint', 'start_date', $this->dateTime());
        $this->addColumn('sprint', 'finish_date', $this->dateTime());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('sprint', 'finish_date');
        $this->dropColumn('sprint', 'start_date');
    }
}
