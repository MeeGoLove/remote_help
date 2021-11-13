<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%connections}}`.
 */
class m210528_182607_create_connections_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%connections}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'ipaddr' => $this->string()->notNull(),
            'comment' => $this->string(),
            'device_type_id' => $this->integer()->notNull(),
            'unit_id' => $this->integer(),
        ]);
		$this->addForeignKey(
			'fk_device_type_id',  // это "условное имя" ключа
            'connections', // это название текущей таблицы
            'device_type_id', // это имя поля в текущей таблице, которое будет ключом
            'device_types', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
		$this->addForeignKey(
			'fk_unit_id',  // это "условное имя" ключа
            'connections', // это название текущей таблицы
            'unit_id', // это имя поля в текущей таблице, которое будет ключом
            'units', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%connections}}');
		$this->dropForeignKey(
              'fk_device_type_id',
              'connections'
          );
		$this->dropForeignKey(
              'fk_unit_id',
              'connections'
          );
    }
}
