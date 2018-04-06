<?php

use yii\db\Migration;

/**
 * Class m180406_125204_add_table_relation
 */
class m180406_125204_add_table_relation extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('relation', [
            'id' => $this->primaryKey(),
            'from_issue' => $this->integer()->notNull(),
            'to_issue' => $this->integer()->notNull(),
            'comment' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('relation');
    }
}
