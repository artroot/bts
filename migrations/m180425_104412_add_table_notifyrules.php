<?php

use yii\db\Migration;

/**
 * Class m180425_104412_add_table_notifyrules
 */
class m180425_104412_add_table_notifyrules extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('notifyrule', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'chapter' => $this->integer(),
            'mail' => $this->boolean(),
            'telegram' => $this->boolean(),
            'owner' => $this->boolean(),
            'performer' => $this->boolean(),
            'all' => $this->boolean(),
            'create' => $this->boolean(),
            'update' => $this->boolean(),
            'delete' => $this->boolean(),
            'done' => $this->boolean(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('notifyrule');
    }

}
