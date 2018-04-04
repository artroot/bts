<?php

use yii\db\Migration;

/**
 * Class m180404_200154_add_user_id_and_create_date_in_comment_table
 */
class m180404_200154_add_user_id_and_create_date_in_comment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('comment', 'user_id', $this->integer(11)->notNull());
        $this->addColumn('comment', 'create_date', $this->timestamp());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('comment','user_id');
        $this->dropColumn('comment','create_date');
    }

}
