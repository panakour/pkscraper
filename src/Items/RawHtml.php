<?php

namespace Pkscraper\Items;

class RawHtml extends Item
{

    protected function doExtract()
    {
        $this->extractedValue = $this->originalExtractedValue = $this->domCrawler->extractHtmlByXpath($this->selector);
    }
}