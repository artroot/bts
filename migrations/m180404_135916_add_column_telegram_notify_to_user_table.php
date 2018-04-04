<?php

use yii\db\Migration;

/**
 * Class m180404_135916_add_column_telegram_notify_to_user_table
 */
class m180404_135916_add_column_telegram_notify_to_user_table extends Migration
{

    public function up()
    {
        $this->addColumn('user', 'telegram_notify', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('user', 'telegram_notify');
    }

}
