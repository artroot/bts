<?php

use yii\db\Migration;

/**
 * Class m180413_105136_create_table_prototype
 */
class m180413_105136_create_table_prototype extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('prototype', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'path' => $this->string(255)->notNull(),
            'issue_id' => $this->integer(),
        ]);

        $this->addForeignKey('fk-prototype-issue_id', 'prototype', 'issue_id', 'issue', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('fk-prototype-issue_id', 'prototype');
        $this->dropTable('prototype');
    }

}
