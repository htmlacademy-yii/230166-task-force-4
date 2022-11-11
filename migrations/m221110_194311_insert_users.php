<?php

use yii\db\Migration;

/**
 * Class m221110_194311_insert_users
 */
class m221110_194311_insert_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('user', [
            'role',
            'rating',
            'count_feedbacks',
            'count_failed_tasks',
            'email',
            'name',
            'password',
            'avatar',
            'date_of_birth'
        ],
        [
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
        echo "m221110_194311_insert_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221110_194311_insert_users cannot be reverted.\n";

        return false;
    }
    */
}
