<?php

use yii\db\Migration;

/**
 * Class m221107_162613_insert_cities
 */
class m221107_162613_insert_cities extends Migration
{
    public function getQuery()
    {
        return require 'data/sql/city.php';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute($this->getQuery());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221107_162613_insert_cities cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221107_162613_insert_cities cannot be reverted.\n";

        return false;
    }
    */
}
