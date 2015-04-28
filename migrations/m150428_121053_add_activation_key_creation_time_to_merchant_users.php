<?php

use yii\db\Schema;
use yii\db\Migration;

class m150428_121053_add_activation_key_creation_time_to_merchant_users extends Migration
{
    public function up()
    {
        $this->addColumn('{{%merchant_users}}', 'activation_key_creation_time', 'int(11)');
    }

    public function down()
    {
        $this->dropColumn('{{%merchant_users}}', 'activation_key_creation_time');
        return true;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
