<?php

namespace Pkscraper\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;

class GuzzleClient extends HttpClient
{

    protected $client;

    protected $headers = [];

    protected $proxy = [];

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    public function setProxy($proxyHttp = 'socks5://127.0.0.1:9050', $proxyHttps = 'socks5://127.0.0.1:9050', $noproxy = [])
    {
        $this->proxy = [
            'http' => $proxyHttp,
            'https' => $proxyHttps,
            'no' => $noproxy,
        ];
    }

    public function newClient()
    {
        $this->client = new Client([
            'proxy' => $this->proxy,
            'headers' => $this->headers
        ]);
    }

    public function concurrentRequests(array $uri)
    {
        $requests = function ($total) use ($uri) {
            for ($i = 0; $i < $total; $i++) {
                yield function () use ($uri, $i) {
                    return $this->client->requestAsync('GET', $uri[$i]);
                };
            }
        };

        $pool = Pool::batch($this->client, $requests(count($uri)), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
            },
        ]);

        return $pool;
    }


    public function doGetRequest(string $uri)
    {
        $this->setCurrentUrlWithoutPath($uri);

        return $this->client->request('GET', $uri);
    }


}