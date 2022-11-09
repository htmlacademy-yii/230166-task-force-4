<?php

namespace app\components;

use yii\base\Widget;

class StarsWidget extends Widget
{
    public $className;
    public $rating;

    public function init()
    {
        parent::init();

        if ($this->className === null) {
            $this->className = 'stars';
        }

        if ($this->rating === null) {
            $this->rating = 0;
        }
    }

    public function run()
    {
        return $this->render('stars', ['className' => $this->className, 'rating' => $this->rating]);
    }
}