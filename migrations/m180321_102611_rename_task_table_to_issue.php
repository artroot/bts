<?php

use yii\db\Migration;

/**
 * Class m180321_102611_rename_task_table_to_issue
 */
class m180321_102611_rename_task_table_to_issue extends Migration
{
   public function up()
    {
        $this->renameTable('task', 'issue');

        $this->renameColumn('issue', 'tasktype_id', 'issuetype_id');
        $this->renameColumn('issue', 'taskstatus_id', 'issuestatus_id');
        $this->renameColumn('issue', 'taskpriority_id', 'issuepriority_id');

        $this->renameColumn('issue', 'parenttask_id', 'parentissue_id');
        $this->renameColumn('issue', 'relatedtask_id', 'relatedissue_id');

        $this->renameColumn('taskviewer', 'task_id', 'issue_id');
        $this->renameColumn('comment', 'task_id', 'issue_id');
        $this->renameColumn('attachment', 'task_id', 'issue_id');
    }

    public function down()
    {
        $this->renameTable('issue', 'task');

        $this->renameColumn('task', 'issuetype_id', 'tasktype_id');
        $this->renameColumn('task', 'issuestatus_id', 'taskstatus_id');
        $this->renameColumn('task', 'issuepriority_id', 'taskpriority_id');

        $this->renameColumn('task', 'parentissue_id', 'parenttask_id');
        $this->renameColumn('task', 'relatedissue_id', 'relatedtask_id');

        $this->renameColumn('taskviewer', 'issue_id', 'task_id');
        $this->renameColumn('comment', 'issue_id', 'task_id');
        $this->renameColumn('attachment', 'issue_id', 'task_id');
    }
}
