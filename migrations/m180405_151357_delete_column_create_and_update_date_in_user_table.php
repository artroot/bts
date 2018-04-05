<?php

use yii\db\Migration;

/**
 * Class m180405_151357_delete_column_create_and_update_date_in_user_table
 */
class m180405_151357_delete_column_create_and_update_date_in_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('user', 'created_at');
        $this->dropColumn('user', 'updated_at');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('user', 'created_at', $this->dateTime());
        $this->addColumn('user', 'updated_at', $this->dateTime());
    }
}
