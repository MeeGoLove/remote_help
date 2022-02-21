<?php

use yii\db\Migration;

/**
 * Class m220221_173004_change_connections_types_table
 */
class m220221_173004_change_connections_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('connection_types', 'protocol_link_readonly', $this->string(255)->after('protocol_link'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('connection_types', 'protocol_link_readonly');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220221_173004_change_connections_types_table cannot be reverted.\n";

        return false;
    }
    */
}
