<?php

use yii\db\Migration;

/**
 * Class m230112_075654_change_connections_types
 */
class m230112_075654_change_connections_types extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('connection_types', 'protocol_link_telnet', $this->string(255)->after('protocol_link_readonly'));
        $this->addColumn('connection_types', 'protocol_link_file_transfer', $this->string(255)->after('protocol_link_telnet'));
        $this->addColumn('connection_types', 'protocol_link_power', $this->string(255)->after('protocol_link_file_transfer'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('connection_types', 'protocol_link_telnet');   
        $this->dropColumn('connection_types', 'protocol_link_file_transfer');
        $this->dropColumn('connection_types', 'protocol_link_power');     
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230112_075654_change_connections_types cannot be reverted.\n";

        return false;
    }
    */
}
