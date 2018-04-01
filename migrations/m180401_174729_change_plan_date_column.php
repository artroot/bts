<?php

use yii\db\Migration;

/**
 * Class m180401_174729_change_plan_date_column
 */
class m180401_174729_change_plan_date_column extends Migration
{

    public function up()
    {
        $this->renameColumn('issue', 'plan_date', 'deadline');
        $this->alterColumn('issue', 'deadline', $this->string(255));
    }

    public function down()
    {
        $this->renameColumn('issue', 'deadline', 'plan_date');
        $this->alterColumn('issue', 'plan_date', $this->date());
    }
}
