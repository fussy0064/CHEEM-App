<?php

use yii\db\Migration;

class m260916_000002_create_service_request_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%service_request}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category' => $this->string(50)->notNull(), // water, waste, pest, safety, risk, economic
            'location' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'urgency' => $this->string(10)->notNull()->defaultValue('medium'), // low, medium, high
            'status' => $this->string(20)->notNull()->defaultValue('pending'), // pending, in_progress, resolved
            'photo_path' => $this->string(255)->null(),
            'reference_number' => $this->string(20)->notNull()->unique(),
            'assigned_to' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey(
            'fk-service_request-user_id',
            '{{%service_request}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-service_request-assigned_to',
            '{{%service_request}}',
            'assigned_to',
            '{{%user}}',
            'id',
            'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-service_request-user_id', '{{%service_request}}');
        $this->dropForeignKey('fk-service_request-assigned_to', '{{%service_request}}');
        $this->dropTable('{{%service_request}}');
    }
}
