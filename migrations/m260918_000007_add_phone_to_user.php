<?php

use yii\db\Migration;

class m260918_000007_add_phone_to_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'phone_number', $this->string(20)->null()->after('email'));
        $this->addColumn('{{%user}}', 'phone_verified', $this->smallInteger()->notNull()->defaultValue(0)->after('phone_number'));
        $this->addColumn('{{%user}}', 'otp_hash', $this->string(255)->null());
        $this->addColumn('{{%user}}', 'otp_expires_at', $this->integer()->null());

        // Existing users (created before this feature) are grandfathered in as verified,
        // so admins/officers already in the system aren't locked out.
        $this->update('{{%user}}', ['phone_verified' => 1]);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'phone_number');
        $this->dropColumn('{{%user}}', 'phone_verified');
        $this->dropColumn('{{%user}}', 'otp_hash');
        $this->dropColumn('{{%user}}', 'otp_expires_at');
    }
}
