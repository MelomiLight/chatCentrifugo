<?php

namespace App\Actions;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;

class CentrifugoAction
{
    const API_PATH = '/api';
    protected $config = [];
    protected HttpClient $httpClient;
    protected $centrifugoProperties;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->centrifugoProperties = Config::get('broadcasting.connections.centrifugo');
        $this->config = [
            'token_hmac_secret_key' => $this->centrifugoProperties['token_hmac_secret_key'],
            'api_key' => $this->centrifugoProperties['api_key'],
            'url' => $this->centrifugoProperties['url'],
            'verify' => $this->centrifugoProperties['verify'],
            'ssl_key' => $this->centrifugoProperties['ssl_key'], // Self-Signed SSl Key for Host (require verify=true)
            'use_namespace' => $this->centrifugoProperties['use_namespace'],
            'default_namespace' => $this->centrifugoProperties['default_namespace'],
            'private_namespace' => $this->centrifugoProperties['private_namespace'],
            'presence_namespace' => $this->centrifugoProperties['presence_namespace'],
        ];
    }


    public function publish(string $channel, array $data, $skipHistory = false)
    {
        return $this->send('publish', [
            'channel' => $channel,
            'data' => $data,
            'skip_history' => $skipHistory,
        ]);
    }

    public function send($method, array $params = [])
    {
        $json = json_encode(['method' => $method, 'params' => $params]);

        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'apikey ' . $this->config['api_key'],
        ];

        try {
            $url = parse_url($this->prepareUrl());

            $config = collect([
                'headers' => $headers,
                'body' => $json,
                'http_errors' => true,
            ]);

            if (isset($url['scheme']) && $url['scheme'] == 'https') {
                $config->put('verify', collect($this->config)->get('verify', false));

                if (collect($this->config)->get('ssl_key')) {
                    $config->put('ssl_key', collect($this->config)->get('ssl_key'));
                }
            }

            $response = $this->httpClient->post($this->prepareUrl(), $config->toArray());

            $result = json_decode((string)$response->getBody(), true);
        } catch (ClientException $e) {
            $result = [
                'method' => $method,
                'error' => $e->getMessage(),
                'body' => $params,
            ];
        }

        return $result;
    }

    protected function prepareUrl(): string
    {
        $address = rtrim('http://localhost:8000', '/');

        if (substr_compare($address, static::API_PATH, -strlen(static::API_PATH)) !== 0) {
            $address .= static::API_PATH;
        }
        //$address .= '/';

        return $address;
    }
}
