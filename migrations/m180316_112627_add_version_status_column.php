<?php

use yii\db\Migration;

/**
 * Class m180316_112627_add_version_status_column
 */
class m180316_112627_add_version_status_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('version', 'status', $this->boolean());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('version', 'status');
    }
}
