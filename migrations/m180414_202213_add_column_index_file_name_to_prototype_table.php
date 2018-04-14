<?php

use yii\db\Migration;

/**
 * Class m180414_202213_add_column_index_file_name_to_prototype_table
 */
class m180414_202213_add_column_index_file_name_to_prototype_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('prototype', 'index_file_name', $this->string()->defaultValue('index.html'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('prototype', 'index_file_name');
    }
}
