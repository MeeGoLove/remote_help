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
        $this->addForeignKey(
            'fk_default_connection_id',  // это "условное имя" ключа
            'connection_stats', // это название текущей таблицы
            'connection_id', // это имя поля в текущей таблице, которое будет ключом
            'connections', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%connection_stats}}');
        $this->dropForeignKey(
            'fk_default_connection_id',
            'connection_stats'
        );
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
