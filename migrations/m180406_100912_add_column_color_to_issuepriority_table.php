<?php

use yii\db\Migration;

/**
 * Class m180406_100912_add_column_color_to_issuepriority_table
 */
class m180406_100912_add_column_color_to_issuepriority_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('issuepriority', 'color', $this->string(8));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('issuepriority', 'color');
    }
}
