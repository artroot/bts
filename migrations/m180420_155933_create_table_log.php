<?php

use yii\db\Migration;

/**
 * Class m180420_155933_create_table_log
 */
class m180420_155933_create_table_log extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('log', [
            'id' => $this->primaryKey(),
            'action' => $this->string(255),
            'model' => $this->string(255),
            'model_id' => $this->integer(),
            'data_old' => $this->text(),
            'data_new' => $this->text(),
            'date' => $this->timestamp(),
            'user_id' => $this->integer()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('log');
    }
}
