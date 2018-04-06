<?php

use yii\db\Migration;

/**
 * Class m180406_110912_add_default_issue_types
 */
class m180406_110912_add_default_issue_types extends Migration
{
    private static $types = [
        'Bug',
        'Task',
        'History',
        'Feature',
    ];

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        foreach (self::$types as $type){
            $this->insert('issuetype', [
                'name' => $type
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        foreach (self::$types as $id => $name){
            $this->delete('issuetype', [
                'name' => $name
            ]);
        }
    }
}
