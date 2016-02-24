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
        $message['alert'] = isset($message['alert']) ? $message['alert'] : '';
        $message['device'] = isset($message['device']) ? $message['device'] : '';

        $data = [
            "request_id" => "1234­1234­1234­1234",
            "campaign_key" => $campaignName,
            "target_type" => "profile",
            "messages" => [[
                "target" => [
                    "profile" => [
                        "criteria" => $this->criteria,
                        "op" => "or"
                    ]
                ],
                "alert" => $message['alert'],
                $message['device']
            ]]
        ];

        try {
            return $this->client->request('POST', $this->app_key, ['json' => $data]);

        } catch (ClientException $e) {
            return false;
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