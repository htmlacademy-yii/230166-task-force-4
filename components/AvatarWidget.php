<?php

namespace app\components;

use yii\base\Widget;

class AvatarWidget extends Widget
{
    public $userId;
    public $src;
    public $linkClass;
    public $imageClass;
    public $width;
    public $height;
    public $alt;

    public function init()
    {
        parent::init();

        if ($this->src === null) {
            $this->src = '/img/default-avatar.png';
        }

        if ($this->linkClass === null) {
            $this->linkClass = '';
        }

        if ($this->imageClass === null) {
            $this->imageClass = 'card-photo';
        }

        if ($this->width === null) {
            $this->width = 190;
        }

        if ($this->height === null) {
            $this->height = 190;
        }

        if ($this->alt === null) {
            $this->alt = 'Фото пользователя';
        }
    }

    public function run()
    {
        return $this->render('avatar', [
            'userId' => $this->userId,
            'src' => $this->src,
            'linkClass' => $this->linkClass,
            'imageClass' => $this->imageClass,
            'width' => $this->width,
            'height' => $this->height,
            'alt' => $this->alt
        ]);
    }
}