<?php

use yii\db\Migration;

/**
 * Class m180417_113127_add_column_plan_date_to_version_table
 */
class m180417_113127_add_column_plan_date_to_version_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('version', 'plan_date', $this->dateTime());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('version', 'plan_date');
    }
}
