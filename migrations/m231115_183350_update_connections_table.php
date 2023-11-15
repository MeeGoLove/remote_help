<?php

use yii\db\Migration;

/**
 * Class m231115_183350_update_connections_table
 */
class m231115_183350_update_connections_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('connections', 'hostname', $this->string()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231115_183350_update_connections_table cannot be reverted.\n";
        //$this->dropColumn('connections', 'hostname');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231115_183350_update_connections_table cannot be reverted.\n";

        return false;
    }
    */
}
