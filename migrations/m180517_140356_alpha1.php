<?php

use yii\db\Migration;

/**
 * Class m180517_140356_alpha1
 */
class m180517_140356_alpha1 extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('user', [
           'id' => $this->primaryKey(),
           'usertype_id' => $this->integer(),
           'username' => $this->string(255)->unique()->notNull(),
           'password' => $this->string(255)->notNull(),
           'password_hash' => $this->text(),
           'password_reset_token' => $this->text(),
           'email' => $this->string(255),
           'auth_key' => $this->text(),
           'status' => $this->integer(),
           'telegram_key' => $this->string(255),
           'telegram_notify' => $this->boolean(),
           'mail_notify' => $this->boolean(),
           'first_name' => $this->string(255),
           'last_name' => $this->string(255)
        ]);

        $this->createTable('issue', [
           'id' => $this->primaryKey(),
           'name' => $this->string(255)->notNull(),
           'description' => $this->text(),
           'create_date' => $this->dateTime(),
           'finish_date' => $this->dateTime(),
           'deadline' => $this->string(255),
           'issuetype_id' => $this->integer(),
           'issuepriority_id' => $this->integer(),
           'issuestatus_id' => $this->integer(),
           'sprint_id' => $this->integer(),
           'resolved_version_id' => $this->integer(),
           'detected_version_id' => $this->integer(),
           'owner_id' => $this->integer(),
           'performer_id' => $this->integer(),
           'project_id' => $this->integer(),
           'progress_time' => $this->integer()
        ]);

        $this->createTable('notifyrule', [
           'id' => $this->primaryKey(),
           'user_id' => $this->integer(),
           'chapter_id' => $this->integer(),
           'mail' => $this->boolean(),
           'telegram' => $this->boolean(),
           'owner' => $this->boolean(),
           'performer' => $this->boolean(),
           'all' => $this->boolean(),
           'create' => $this->boolean(),
           'update' => $this->boolean(),
           'delete' => $this->boolean(),
           'done' => $this->boolean()
        ]);

        $this->createTable('sprint', [
           'id' => $this->primaryKey(),
           'name' => $this->string(255),
           'version_id' => $this->integer(),
           'project_id' => $this->integer(),
           'start_date' => $this->dateTime(),
           'finish_date' => $this->dateTime(),
           'status' => $this->boolean()
        ]);

        $this->createTable('issuepriority', [
           'id' => $this->primaryKey(),
           'name' => $this->string(255),
           'level' => $this->integer(),
           'color' => $this->string(8)
        ]);

        $this->createTable('version', [
           'id' => $this->primaryKey(),
           'project_id' => $this->integer(),
           'name' => $this->string(255)->notNull(),
           'description' => $this->text(),
           'start_date' => $this->dateTime(),
           'finish_date' => $this->dateTime(),
           'plan_date' => $this->dateTime(),
           'status' => $this->boolean(),
        ]);

        $this->createTable('log', [
           'id' => $this->primaryKey(),
           'action' => $this->string(255),
           'model' => $this->string(255),
           'model_id' => $this->integer(),
           'data_old' => $this->text(),
           'data_new' => $this->text(),
           'date' => $this->timestamp(),
           'user_id' => $this->integer(),
           'issue_id' => $this->integer()
        ]);

        $this->createTable('comment', [
           'id' => $this->primaryKey(),
           'issue_id' => $this->integer(),
           'user_id' => $this->integer(),
           'text' => $this->text(),
           'create_date' => $this->timestamp(),
        ]);

        $this->createTable('project', [
           'id' => $this->primaryKey(),
           'name' => $this->string(255)->notNull(),
           'team_id' => $this->integer(),
           'description' => $this->text(),
           'logo' => $this->text()
        ]);

        $this->createTable('issuestatus', [
           'id' => $this->primaryKey(),
           'name' => $this->string(255)->notNull(),
           'state_id' => $this->integer(),
           'count_progress_from' => $this->boolean(),
           'count_progress_to' => $this->boolean()
        ]);

        $this->createTable('prototype', [
           'id' => $this->primaryKey(),
           'name' => $this->string(255),
           'path' => $this->string(255),
           'issue_id' => $this->integer(),
           'index_file_name' => $this->string(255)
        ]);

        $this->createTable('attachment', [
           'id' => $this->primaryKey(),
           'issue_id' => $this->integer(),
           'file' => $this->text(),
           'type' => $this->string(255),
           'base_name' => $this->string(255)
        ]);

        $this->createTable('telegram', [
           'token' => $this->string(255),
           'base_url' => $this->string(255),
           'update_id' => $this->integer(20),
           'status' => $this->boolean()
        ]);

        $this->createTable('relation', [
           'id' => $this->primaryKey(),
           'from_issue' => $this->integer(),
           'to_issue' => $this->integer(),
           'comment' => $this->text()
        ]);

        $this->createTable('teamusers', [
           'id' => $this->primaryKey(),
           'team_id' => $this->integer(),
           'user_id' => $this->integer(),
           'teamrole_id' => $this->integer(),
        ]);

        $this->createTable('teamrole', [
           'id' => $this->primaryKey(),
           'rolename' => $this->string(255),
        ]);

        $this->createTable('observer', [
           'id' => $this->primaryKey(),
           'issue_id' => $this->integer(),
           'user_id' => $this->integer(),
        ]);

        $this->createTable('telegramupdate', [
           'update_id' => $this->bigInteger(20)
        ]);

        $this->createTable('issuetype', [
           'id' => $this->primaryKey(),
           'name' => $this->string(255)->notNull(),
        ]);

        $this->createTable('team', [
           'id' => $this->primaryKey()
        ]);

        $this->createTable('usertype', [
           'id' => $this->primaryKey(),
           'name' => $this->string(255)->notNull(),
        ]);

        /*$this->createTable('migration', [
           'version' => $this->primaryKey(),
           'apply_time' => $this->integer()
        ]);*/


        // Create default user admin / koala
        $this->insert('user', [
            'usertype_id' => 1,
            'username' => 'admin',
            'password_hash' => '$2y$13$8TnW/rJotk9323E.6SaCT.LjnGJoZzpL7luJR4FgBSfcjCiCjvwqK',
            'auth_key' => 'mKSGw-3J0vYaH_AhS1KF7jSlFA0eoDTT',
            'status' => 10,
            'telegram_notify' => 0,
            'mail_notify' => 0,
            'first_name' => 'Koala',
            'last_name' => 'Admin'
        ]);

        $issuepriorities = [
          [
              'name' => 'critical',
              'level' => '10',
              'color' => '#ec0000'
          ],
          [
              'name' => 'high',
              'level' => '20',
              'color' => '#ec8e00'
          ],
          [
              'name' => 'normal',
              'level' => '30',
              'color' => '#e4dd01'
          ],
          [
              'name' => 'low',
              'level' => '40',
              'color' => '#38c509'
          ],
          [
              'name' => 'insignificant',
              'level' => '50',
              'color' => '#01e4a5'
          ]
        ];

        foreach ($issuepriorities as $issuepriority) $this->insert('issuepriority', $issuepriority);

        $issuestatuses = [
          [
              'name' => 'Closed',
              'state_id' => '2',
              'count_progress_from' => '0',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'New',
              'state_id' => '0',
              'count_progress_from' => '1',
              'count_progress_to' => '0',
          ],
          [
              'name' => 'In Progress',
              'state_id' => '1',
              'count_progress_from' => '1',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Need Test',
              'state_id' => '1',
              'count_progress_from' => '1',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Open',
              'state_id' => '0',
              'count_progress_from' => '1',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Rejected',
              'state_id' => '2',
              'count_progress_from' => '0',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Deferred',
              'state_id' => '2',
              'count_progress_from' => '0',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Not Repeated',
              'state_id' => '0',
              'count_progress_from' => '1',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Duplicate',
              'state_id' => '2',
              'count_progress_from' => '1',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Resolved',
              'state_id' => '2',
              'count_progress_from' => '1',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Not Resolved',
              'state_id' => '0',
              'count_progress_from' => '1',
              'count_progress_to' => '1',
          ],
          [
              'name' => 'Reopened',
              'state_id' => '0',
              'count_progress_from' => '1',
              'count_progress_to' => '1',
          ]
        ];

        foreach ($issuestatuses as $issuestatus) $this->insert('issuestatus', $issuestatus);

        $issuetypes = [
          [
              'name' => 'Bug'
          ],
          [
              'name' => 'Task'
          ],
          [
              'name' => 'History'
          ],
          [
              'name' => 'Feature'
          ],
        ];

        foreach ($issuetypes as $issuetype) $this->insert('issuetype', $issuetype);


        $this->insert('notifyrule', [
            'user_id' => 1,
            'chapter' => 3,
            'mail' => 0,
            'telegram' => 0,
            'owner' => 0,
            'performer' => 0,
            'all' => 0,
            'create' => 0,
            'update' => 0,
            'delete' => 0,
            'done' => 0
        ]);
        $this->insert('notifyrule', [
            'user_id' => 1,
            'chapter' => 1,
            'mail' => 0,
            'telegram' => 0,
            'owner' => 0,
            'performer' => 0,
            'all' => 0,
            'create' => 0,
            'update' => 0,
            'delete' => 0,
            'done' => 0
        ]);
        $this->insert('notifyrule', [
            'user_id' => 1,
            'chapter' => 2,
            'mail' => 0,
            'telegram' => 0,
            'owner' => 0,
            'performer' => 0,
            'all' => 0,
            'create' => 0,
            'update' => 0,
            'delete' => 0,
            'done' => 0
        ]);
        $this->insert('notifyrule', [
            'user_id' => 1,
            'chapter' => 0,
            'mail' => 0,
            'telegram' => 0,
            'owner' => 0,
            'performer' => 0,
            'all' => 0,
            'create' => 0,
            'update' => 0,
            'delete' => 0,
            'done' => 0
        ]);


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180517_140356_alpha1 cannot be reverted.\n";

        return false;
    }
}
