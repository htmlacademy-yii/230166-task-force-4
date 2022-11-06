<?php

namespace app\controllers;

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
        return ArrayHelper::getValue($this->data, 'GeoObjectCollection.featureMember.GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName');
    }

    public function getAddress()
    {
        return ArrayHelper::getValue($this->data, 'GeoObjectCollection.featureMember.GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.AddressLine');
    }

    public function getPoint()
    {
        return ArrayHelper::getValue($this->data, 'GeoObjectCollection.featureMember.GeoObject.Point.pos');
    }

    public function getLat()
    {
        return $this-> getPoint()[0];
    }

    public function getLng()
    {
        return $this-> getPoint()[1];
    }
}

