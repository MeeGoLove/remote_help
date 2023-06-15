<?php

use yii\db\Migration;

/**
 * Class m230614_192945_change_connections_table
 */
class m230614_192945_change_connections_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('connections', 'count_connect', $this->integer()->after('unit_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('connections', 'count_connect');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230614_192945_change_connections_table cannot be reverted.\n";

        return false;
    }
    */
}
