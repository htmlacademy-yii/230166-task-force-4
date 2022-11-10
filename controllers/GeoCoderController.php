<?php

namespace app\controllers;

use Exception;
use Yii;
use GuzzleHttp\Client;
use yii\helpers\ArrayHelper;

class GeoCoderController extends SecuredController
{
    public $data;

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

    public function getName()
    {
        return ArrayHelper::getValue($this->data, $this->getStartLine() . 'name');
    }

    public function getPoint()
    {
        return ArrayHelper::getValue($this->data, $this->getStartLine() . 'Point.pos');
    }

    public function getLat()
    {
        return explode(' ', $this->getPoint())[1];
    }

    public function getLng()
    {
        return explode(' ', $this->getPoint())[0];
    }

    public function getStartLine(): ?string
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

