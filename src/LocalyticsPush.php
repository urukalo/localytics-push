<?php

namespace LocalyticsPush;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class LocalyticsPush
{

    private $client;
    private $criteria = [];
    private $app_key;

    public function __construct($api_key, $api_sec, $app_key)
    {
        $this->app_key = $app_key;

        $this->client = new Client([
            'base_uri' => 'https://messaging.localytics.com/v2/push/',
            'auth' => [$api_key, $api_sec],
            'debug' => false
        ]);
    }


    /**
     * @param array $message
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send($message = [], $op = 'or')
    {
        $data = [
            "request_id" => str_random(),
            "target_type" => "profile",
            "messages" => [
                array_merge(
                    [
                        "target" => [
                            "profile" =>
                                $this->makeCriteria($op)
                        ]
                    ], $message)
            ]
        ];

        //echo '<pre>' . json_encode($data); dd();

        try {
            return ['data' => $data, 'status' => $this->client->request('POST', $this->app_key, ['json' => $data])->getStatusCode()];
        } catch (ClientException $e) {
            return ['data' => $data, 'status' => $e->getMessage()];
        }
    }

    /**
     * @param $campaignName
     * @param array $message
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function createCampaign($campaignName, $message = [], $op = 'or')
    {
        $data = [
            "request_id" => str_random(),
            "campaign_key" => $campaignName,
            "target_type" => "profile",
            "messages" => [
                array_merge(
                    [
                        "target" => [
                            "profile" =>
                                $this->makeCriteria($op)
                        ]
                    ], $message)
            ]
        ];

        //echo '<pre>' . json_encode($data); dd();

        try {
            return ['data' => $data, 'status' => $this->client->request('POST', $this->app_key, ['json' => $data])->getStatusCode()];
        } catch (ClientException $e) {
            return ['data' => $data, 'status' => $e->getMessage()];
        }
    }

    private function makeCriteria($op)
    {

        return ["criteria" => $this->criteria, 'op' => $op];
    }

    public function equalTo($key, $values, $type = "int", $scope = "LocalyticsApplication")
    {

        $this->criteria[] = [
            "key" => $key,
            "scope" => $scope,
            "type" => $type,
            "op" => "in",
            "values" => $values
        ];

    }

    public function containedIn($key, $values, $type = "string", $scope = "LocalyticsApplication")
    {

        $this->criteria[] = [
            "key" => $key,
            "scope" => $scope,
            "type" => $type,
            "op" => "in",
            "values" => [
                $values
            ]
        ];

    }

    public function isNull($key, $type = "string", $scope = "LocalyticsApplication")
    {

        $this->criteria[] = [
            "key" => $key,
            "scope" => $scope,
            "type" => $type,
            "op" => "is_null"
        ];

    }

    public function notContainedIn($key, $values, $type = "string", $scope = "LocalyticsApplication")
    {

        $this->criteria[] = [
            "key" => $key,
            "scope" => $scope,
            "type" => $type,
            "op" => "not_in",
            "values" => [
                $values
            ]
        ];

    }

    public function addCriteria($op, $key, $values, $type = "int", $scope = "LocalyticsApplication")
    {

        $this->criteria[] = [
            "key" => $key,
            "scope" => $scope,
            "type" => $type,
            "op" => $op,
            "values" => $values
        ];

    }

}