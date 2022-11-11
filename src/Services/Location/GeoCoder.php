<?php

namespace TaskForce\Services\Location;

use Yii;
use yii\helpers\ArrayHelper;
use GuzzleHttp\Client;
use TaskForce\Services\Location\Interfaces\GeoCoderInterface;

class GeoCoder implements GeoCoderInterface
{
    public $data;

    /**
     * получаем данные из геокодера
     *
     * @param  mixed $location
     * @return void
     */
    public function __construct($location)
    {
        $url = Yii::$app->params['geocoder']['url'];
        $apiKey = Yii::$app->params['geocoder']['api_key'];
        $client = new Client();

        $response = $client->request('GET', $url, [
            'query' => ['apikey' => $apiKey, 'geocode' => $location]
        ]);

        $content = $response->getBody()->getContents();

        $xml = simplexml_load_string($content);
        $json = json_encode($xml);
        $this->data = json_decode($json, true);

        if (!is_array($this->data)) {
            throw new \Exception();
        }
    }

    /**
     * получаем им города, если получено название города
     * в другом случае вывод этой строки непредсказуем
     *
     * @return string
     */
    public function getName(): string
    {
        return ArrayHelper::getValue($this->data, $this->getStartLine() . 'name');
    }

    /**
     * получена строка координат
     *
     * @return string
     */
    public function getPoint(): string
    {
        return ArrayHelper::getValue($this->data, $this->getStartLine() . 'Point.pos');
    }

    /**
     * Получаем широту
     *
     * @return string
     */
    public function getLat(): string
    {
        return explode(' ', $this->getPoint())[1];
    }

    /**
     * получаем долготу
     *
     * @return string
     */
    public function getLng(): string
    {
        return explode(' ', $this->getPoint())[0];
    }

    /**
     * получаем стартовую строку для поиска
     *
     * @return string
     */
    private function getStartLine(): ?string
    {
        $found = (int) ArrayHelper::getValue($this->data, 'GeoObjectCollection.metaDataProperty.GeocoderResponseMetaData.found');

        if ($found === 1) {
            return 'GeoObjectCollection.featureMember.GeoObject.';
        } elseif ($found > 1) {
            return 'GeoObjectCollection.featureMember.0.GeoObject.';
        } else {
            return null;
        }
    }
}

