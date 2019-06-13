<?php


namespace Pkscraper\Http;


class CurlClient
{


    function __construct()
    {
        $torSocks5Proxy = "socks5://10.0.75.1:9050";
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($this->ch, CURLOPT_PROXY, $torSocks5Proxy);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
    }

    public function initialize($i)
    {
        $torSocks5Proxy = "socks5://10.0.75.1:9050";
        $this->ch[$i] = curl_init();
        curl_setopt($this->ch[$i], CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($this->ch[$i], CURLOPT_PROXY, $torSocks5Proxy);
        curl_setopt($this->ch[$i], CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch[$i], CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch[$i], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch[$i], CURLOPT_HEADER, false);
    }

    public function doRequest($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        return curl_exec($this->ch);
    }

    public function tor_new_identity($tor_ip = '10.0.75.1', $control_port = '9051', $auth_code = '')
    {
        //https://w-shadow.com/blog/2008/06/20/tor-how-to-new-identity-with-php/
        //it seems that is not working
    }


    public function concurrentRequests(array $uri)
    {
        //https://stackoverflow.com/questions/9308779/php-parallel-curl-requests
        $nodes = $uri;
        $node_count = count($nodes);
        $this->ch = array();
        $master = curl_multi_init();
        for ($i = 0; $i < $node_count; $i++) {
            $url = $nodes[$i];
            $this->initialize($i);
            curl_multi_add_handle($master, $this->ch[$i]);
        }
        do {
            curl_multi_exec($master, $running);
        } while ($running > 0);

        for ($i = 0; $i < $node_count; $i++) {
            $results[] = curl_multi_getcontent($this->ch[$i]);
        }
        return $results;

    }


}