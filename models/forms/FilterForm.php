<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Category;
use app\models\Task;

class FilterForm extends Model
{
    public $categories = [];
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
            ['categories', 'each', 'rule' => ['exist', 'targetClass' => Category::class, 'targetAttribute' => ['categories' => 'id']]],
            ['noExecutor', 'boolean'],
            ['period', 'in', 'range' => array_keys($this->periodAttributeLabels())]
        ];
    }

    public function getQueryWithFilters(): \yii\db\ActiveQuery
    {
        $query = Task::find()->joinWith(['category'])->where(['task.status' => 'new'])->asArray();

        if ($this->categories) {
            $query->andWhere(['category.id' => $this->categories]);
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