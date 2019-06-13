<?php

namespace Pkscraper\Items;

class HtmlArray extends Item
{
    protected function doExtract()
    {
        $this->extractedValue = $this->originalExtractedValue = $this->domCrawler->extractHtmlArrayByXpath($this->selector);
    }
}