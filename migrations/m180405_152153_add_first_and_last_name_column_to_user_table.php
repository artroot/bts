<?php

use yii\db\Migration;

/**
 * Handles adding first_and_last_name to table `user`.
 */
class m180405_152153_add_first_and_last_name_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'first_name', $this->string(255));
        $this->addColumn('user', 'last_name', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'last_name');
        $this->dropColumn('user', 'first_name');
    }
}
