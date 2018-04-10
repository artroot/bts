<?php

use yii\db\Migration;

/**
 * Class m180410_104338_del_columns_parentissue_relatedissue_version
 */
class m180410_104338_del_columns_parentissue_relatedissue_version extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('issue', 'version_id');
        $this->dropColumn('issue', 'parentissue_id');
        $this->dropColumn('issue', 'relatedissue_id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('issue', 'version_id', $this->integer());
        $this->addColumn('issue', 'parentissue_id', $this->integer());
        $this->addColumn('issue', 'relatedissue_id', $this->integer());
    }
}
