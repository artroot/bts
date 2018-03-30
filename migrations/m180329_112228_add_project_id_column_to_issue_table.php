<?php

use yii\db\Migration;

/**
 * Handles adding project_id to table `issue`.
 */
class m180329_112228_add_project_id_column_to_issue_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('issue', 'project_id', $this->integer(11)->notNull());
        $this->createIndex('idx-issue-project_id', 'issue', 'project_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('issue', 'project_id');
    }
}
