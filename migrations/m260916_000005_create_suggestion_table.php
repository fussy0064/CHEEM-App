<?php

use yii\db\Migration;

class m260916_000005_create_suggestion_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%suggestion}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('new'), // new, reviewed
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey('fk-suggestion-user_id', '{{%suggestion}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-suggestion-user_id', '{{%suggestion}}');
        $this->dropTable('{{%suggestion}}');
    }
}
