<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%device_types}}`.
 */
class m210528_182419_create_device_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%device_types}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
			'default_connection_type_id' => $this->integer()->notNull(),
			'optional_connection_type_id' => $this->integer(),
        ]);
		$this->addForeignKey(
			'fk_default_connection_type_id',  // это "условное имя" ключа
            'device_types', // это название текущей таблицы
            'default_connection_type_id', // это имя поля в текущей таблице, которое будет ключом
            'connection_types', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%device_types}}');
		$this->dropForeignKey(
              'fk_default_connection_type_id',
              'device_types'
          );
    }
}
