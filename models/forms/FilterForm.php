<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Category;
use app\models\Task as TaskActiveRecord;
use TaskForce\Models\Task;

class FilterForm extends Model
{
    public array $categories = [];
    public bool $noExecutor = false;
    public string $period = self::PERIOD_ALL;

    const PERIOD_1_HOUR = '1 hour';
    const PERIOD_12_HOURS = '12 hours';
    const PERIOD_24_HOURS = '24 hours';
    const PERIOD_ALL = 'all';

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels(): array
    {
        return [
            'noExecutor' => 'Без исполнителя',
        ];
    }

    public function periodAttributeLabels(): array
    {
        return [
            self::PERIOD_1_HOUR => '1 час',
            self::PERIOD_12_HOURS => '12 часов',
            self::PERIOD_24_HOURS => '24 часа',
            self::PERIOD_ALL => 'За всё время'
        ];
    }

    public function rules(): array
    {
        return [
            ['categories', 'each', 'rule' => ['exist', 'targetClass' => Category::class, 'targetAttribute' => ['categories' => 'name']]],
            ['noExecutor', 'boolean'],
            ['period', 'in', 'range' => array_keys($this->periodAttributeLabels())]
        ];
    }

    public function getNewTaskQuery(): \yii\db\ActiveQuery
    {
        return TaskActiveRecord::find()
        ->select([
            'task.id',
            'task.created_at',
            'task.title',
            'task.price',
            'task.text',
            'task.status',
            'category.name as category_name',
            'category.label as category_label',
            'city.name as city'
        ])
        ->join('INNER JOIN', 'category', 'task.category_id = category.id')
        ->join('INNER JOIN', 'user', 'task.customer_id = user.id')
        ->join('INNER JOIN', 'city', 'user.city_id = city.id')
        ->where(['task.status' => Task::STATUS_NEW])
        ->asArray();
    }

    public function getQueryWithFilters(): \yii\db\ActiveQuery
    {
        $query = self::getNewTaskQuery();

        if ($this->categories) {
            $query->andWhere(['category.name' => $this->categories]);
        }

        if ($this->noExecutor) {
            $query->andWhere(['task.executor_id' => null]);
        }

        if ($this->period) {
            switch($this->period) {
                case self::PERIOD_1_HOUR:
                    $query->andWhere('task.created_at >= NOW() - INTERVAL 1 HOUR');
                    break;
                case self::PERIOD_12_HOURS:
                    $query->andWhere('task.created_at >= NOW() - INTERVAL 12 HOUR');
                    break;
                case self::PERIOD_12_HOURS:
                    $query->andWhere('task.created_at >= NOW() - INTERVAL 24 HOUR');
                    break;
                default:
                    return $query;
                    break;
            }
        }

        return $query;
    }
}