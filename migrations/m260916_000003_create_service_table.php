<?php

use yii\db\Migration;

class m260916_000003_create_service_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%service}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'category' => $this->string(50)->notNull(),
            'description' => $this->text()->null(),
            'active' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey('fk-service-created_by', '{{%service}}', 'created_by', '{{%user}}', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-service-created_by', '{{%service}}');
        $this->dropTable('{{%service}}');
    }
}
