<?php

namespace Pkscraper\Items;

class Text extends Item
{
    protected function doExtract()
    {
        $this->extractedValue = $this->originalExtractedValue = $this->domCrawler->extractTextByXpath($this->selector);
    }
}