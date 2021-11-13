<?php

use yii\db\Migration;

/**
 * Class m211110_185408_change_connections_types_table
 */
class m211110_185408_change_connections_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('connection_types', 'icon', $this->string(255)->after('protocol_link'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211110_185408_change_connections_types_table cannot be reverted.\n";
        $this->dropColumn('connection_types', 'icon');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211110_185408_change_connections_types_table cannot be reverted.\n";

        return false;
    }
    */
}
