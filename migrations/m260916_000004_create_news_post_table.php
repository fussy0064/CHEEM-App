<?php

use yii\db\Migration;

class m260916_000004_create_news_post_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%news_post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'content' => $this->text()->null(),
            'image_path' => $this->string(255)->null(),
            'active' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey('fk-news_post-created_by', '{{%news_post}}', 'created_by', '{{%user}}', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-news_post-created_by', '{{%news_post}}');
        $this->dropTable('{{%news_post}}');
    }
}
