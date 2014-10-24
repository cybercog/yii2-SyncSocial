<?php

use yii\db\Schema;
use yii\db\Migration;

class m141023_180552_sync_record extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sync_model}}', [
            'model_id' => Schema::TYPE_PK,
            'service_name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'service_id_author' => Schema::TYPE_BIGINT . ' DEFAULT NULL',
            'service_id_post' => Schema::TYPE_BIGINT . ' DEFAULT NULL',
            'time_created' => Schema::TYPE_INTEGER . ' DEFAULT NULL'
        ], $tableOptions);

        return true;
    }

    public function down()
    {
        $this->dropTable('{{%sync_model}}');

        return true;
    }
}
