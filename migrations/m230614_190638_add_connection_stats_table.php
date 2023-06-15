<?php

use yii\db\Migration;

/**
 * Class m230614_190638_add_connection_stats_table
 */
class m230614_190638_add_connection_stats_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%connection_stats}}', [
                'id' => $this->primaryKey(),
                'connection_id' => $this->integer()->notNull(),
                'operator_ip' => $this->string()->notNull(),
                'connection_date' => $this->integer()]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%connection_stats}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230614_190638_add_connection_stats_table cannot be reverted.\n";

        return false;
    }
    */
}
