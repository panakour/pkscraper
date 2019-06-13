<?php

namespace Pkscraper\Dom;

interface DomCrawler
{

    public function setContent($content);

    public function extractTextByXpath(string $xpath): string;

    public function extractHtmlByXpath(string $xpath): string;

    public function extractTextArrayByXpath(string $xpath): array;

    public function extractHtmlArrayByXpath(string $xpath): array;
}