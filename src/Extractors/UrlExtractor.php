<?php

namespace Pkscraper\Extractors;

use Pkscraper\Dom\DomCrawler;
use Pkscraper\Dom\SymfonyDomCrawler;
use Pkscraper\Http\HttpClient;
use Pkscraper\ToolBox;

class UrlExtractor
{

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var DomCrawler
     */
    private $domCrawler;

    /**
     * UrlGenerator constructor.
     * @param HttpClient $httpClient
     * @param DomCrawler $domCrawler
     */
    public function __construct(HttpClient $httpClient, DomCrawler $domCrawler)
    {
        $this->httpClient = $httpClient;

        $this->domCrawler = $domCrawler;
    }


    public static function extract(HttpClient $httpClient, string $url, string $xpath)
    {
        $urlExtractor = new self($httpClient, new SymfonyDomCrawler());
        return $urlExtractor->doExtract($url, $xpath);
    }

    public function doExtract(string $url, string $xpath)
    {
        $response = $this->httpClient->doGetRequest($url);
        $this->domCrawler->setContent($response->getBody()->getContents());
        $urls = $this->domCrawler->extractTextArrayByXpath($xpath);
        if (ToolBox::isRelativeUrl($urls[0])) {
            foreach ($urls as $index => $url) {
                $urls[$index] = $this->httpClient->getCurrentUrlWithoutPath() . $url;
            }
        }
        return $urls;

    }


}