<?php

use yii\db\Migration;

/**
 * Class m180404_140138_add_column_mail_notify_to_user_table
 */
class m180404_140138_add_column_mail_notify_to_user_table extends Migration
{

    public function up()
    {
        $this->addColumn('user', 'mail_notify', $this->boolean()->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('user', 'mail_notify');
    }
}
