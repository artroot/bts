<?php

use yii\db\Migration;

/**
 * Class m180321_105023_set_performer_id_null_default_and_not_unic
 */
class m180321_105023_set_performer_id_null_default_and_not_unic extends Migration
{
    public function up()
    {
        $this->alterColumn('issue', 'performer_id', $this->integer(11)->defaultValue(0));
    }

    public function down()
    {
        $this->alterColumn('task', 'performer_id', $this->integer(11)->notNull()->unique());
    }
}
