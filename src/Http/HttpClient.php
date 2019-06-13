<?php


namespace Pkscraper\Http;


use Pkscraper\ToolBox;

abstract class HttpClient
{
    protected $currentUrlWithoutPath;

    public abstract function concurrentRequests(array $uri);

    public abstract function doGetRequest(string $uri);

    public function getCurrentUrlWithoutPath(): string
    {
        return $this->currentUrlWithoutPath;
    }

    public function setCurrentUrlWithoutPath(string $uri)
    {
        $this->currentUrlWithoutPath = ToolBox::getUrlWithoutPath($uri);
    }
}