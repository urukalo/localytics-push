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

        $this->client = new Client(['base_uri' => 'https://messaging.localytics.com/v2/push/',
            'auth' => [$api_key, $api_sec],
            'debug' => false]);
    }


    /**
     * @param $campaignName
     * @param array $message
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send($campaignName, $message = [])
    {
        $data = [
            "request_id" => str_random(),
            "campaign_key" => $campaignName,
            "target_type" => "profile",
            "messages" => [
                array_merge(
                    ["target" => [
                        "profile" => [
                            "criteria" => $this->criteria,
                            "op" => "or"
                        ]]], $message)
            ]
        ];

        try {
            return $this->client->request('POST', $this->app_key, ['json' => $data]);
        } catch (ClientException $e) {
            return $e->getMessage();
        }
    }

    public function equalTo($key, $values, $type = "string")
    {

        $this->criteria[] = ["key" => $key,
            "scope" => "LocalyticsApplication",
            "type" => $type,
            "op" => "in",
            "values" => [
                $values
            ]
        ];

    }
}