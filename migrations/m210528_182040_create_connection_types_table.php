<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%connection_types}}`.
 */
class m210528_182040_create_connection_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%connection_types}}', [
            'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'protocol_link' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%connection_types}}');
    }
}
