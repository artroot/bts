<?php

use yii\db\Migration;

/**
 * Class m180416_135412_add_column_base_name_to_attachment_table
 */
class m180416_135412_add_column_base_name_to_attachment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('attachment', 'base_name', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('attachment', 'base_name');
    }

}
