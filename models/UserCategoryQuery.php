<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserCategory]].
 *
 * @see UserCategory
 */
class UserCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
