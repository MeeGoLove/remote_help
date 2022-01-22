<?php

use yii\db\Migration;

/**
 * Class m220122_184057_change_connection_types_table
 */
class m220122_184057_change_connection_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('connection_types', 'port', $this->string(255)->after('icon'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('connection_types', 'port');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220122_184057_change_connection_types_table cannot be reverted.\n";

        return false;
    }
    */
}
