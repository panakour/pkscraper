<?php

namespace Pkscraper\Items;


class TextArray extends Item
{
    protected function doExtract()
    {
        $this->extractedValue = $this->originalExtractedValue = $this->domCrawler->extractTextArrayByXpath($this->selector);
    }
}