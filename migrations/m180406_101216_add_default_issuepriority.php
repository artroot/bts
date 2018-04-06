<?php

use yii\db\Migration;

/**
 * Class m180406_101216_add_default_issuepriority
 */
class m180406_101216_add_default_issuepriority extends Migration
{
    // insignificant, low, normal, high, critical
    private static $priority = [
        [
           'name' => 'critical',
           'level' => 10,
           'color' => '#ec0000',
        ],
        [
           'name' => 'high',
           'level' => 20,
           'color' => '#ec8e00',
        ],
        [
           'name' => 'normal',
           'level' => 30,
           'color' => '#e4dd01',
        ],
        [
           'name' => 'low',
           'level' => 40,
           'color' => '#38c509',
        ],
        [
           'name' => 'insignificant',
           'level' => 50,
           'color' => '#01e4a5',
        ],
    ];

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        foreach (self::$priority as $priority){
                $this->insert('issuepriority', $priority);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        foreach (self::$priority as $id => $props){
                $this->delete('issuepriority', [
                    'id' => $id
                ]);
        }
    }
}
