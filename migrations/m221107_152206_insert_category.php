<?php

use yii\db\Migration;

/**
 * Class m221107_152206_insert_category
 */
class m221107_152206_insert_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('category', ['name', 'label'], [
            ['courier', 'Курьерские услуги'],
            ['clean', 'Уборка'],
            ['cargo', 'Переезды'],
            ['neo', 'Компьютерная помощь'],
            ['flat', 'Ремонт квартирный'],
            ['repair', 'Ремонт техники'],
            ['beauty', 'Красота'],
            ['photo', 'Фото'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('category', ['in', 'name', [
            'courier',
            'clean',
            'cargo',
            'neo',
            'flat',
            'repair',
            'beauty',
            'photo',
        ]]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221107_152206_insert_table_category cannot be reverted.\n";

        return false;
    }
    */
}
