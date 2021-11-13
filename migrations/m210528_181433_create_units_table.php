<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%units}}`.
 */
class m210528_181433_create_units_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%units}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'location' => $this->string(),
            'parent_id' => $this->integer()
        ]);
        $this->addForeignKey(
            'fk_parent_id',  // это "условное имя" ключа
            'units', // это название текущей таблицы
            'parent_id', // это имя поля в текущей таблице, которое будет ключом
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
        $this->dropTable('{{%units}}');
        $this->dropForeignKey(
            'fk_parent_id',
            'units'
        );
    }
}
