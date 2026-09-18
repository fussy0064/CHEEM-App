<?php

use yii\db\Migration;

class m260918_000006_add_geo_to_service_request extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%service_request}}', 'latitude', $this->decimal(10, 7)->null()->after('location'));
        $this->addColumn('{{%service_request}}', 'longitude', $this->decimal(10, 7)->null()->after('latitude'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%service_request}}', 'latitude');
        $this->dropColumn('{{%service_request}}', 'longitude');
    }
}
