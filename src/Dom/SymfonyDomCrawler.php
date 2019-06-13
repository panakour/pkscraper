<?php

namespace Pkscraper\Dom;

use Symfony\Component\DomCrawler\Crawler;

class SymfonyDomCrawler extends Crawler implements DomCrawler
{
    public function __construct($node = null)
    {
        parent::__construct($node);
    }

    public function setContent($content)
    {
        parent::add($content);
    }

    public function extractTextByXpath(string $xpath): string
    {
        if ($this->filterXPath($xpath)->count()) {
            return trim($this->filterXPath($xpath)->text());
        }

        return '';
    }

    public function extractHtmlByXpath(string $xpath): string
    {
        if ($this->filterXPath($xpath)->count()) {
            return $this->filterXPath($xpath)->html();
        }

        return '';
    }

    public function extractTextArrayByXpath(string $xpath): array
    {
        return $this->filterXPath($xpath)->each(function ($node) {
            return $node->text();
        });
    }

    public function extractHtmlArrayByXpath(string $xpath): array
    {
        return $this->filterXPath($xpath)->each(function ($node) {
            return $node->html();
        });
    }


}