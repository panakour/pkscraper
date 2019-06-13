<?php

namespace Pkscraper\Extractors;


use DOMDocument;
use Pkscraper\Bag;
use Pkscraper\Cloning\ImageCloner;
use Pkscraper\Collector;
use Pkscraper\Dom\SymfonyDomCrawler;
use Pkscraper\Exceptions\RssLoadException;
use Pkscraper\Items\SafeHtml;
use Pkscraper\Items\TextArray;
use Pkscraper\Items\Text;

class RssExtractor
{

    public static function extract(string $url)
    {
        $dom = new DOMDocument();
        if (!@$dom->load($url)) {
            throw new RssLoadException();
        }
        $bags = [];
        foreach ($dom->getElementsByTagName('item') as $index => $node) {
            $domCrawler = new SymfonyDomCrawler($node);
            $bags[$index] = new Bag($domCrawler->extractTextByXpath('//link'));
            $titleItem = new Text('title', $domCrawler, '//title');
            $domRunnerAfterPurify = function () use ($titleItem) {
                $titleValue = $titleItem->getExtractedValue();
                $this->setAttributes('a', ['target' => '_blank', 'rel' => 'nofollow']);
                \Pkscraper\ToolBox::replaceImagesAttributes($this, $titleValue, 'storage/path');
            };
            $htmlContentItem = new SafeHtml('mainContent', $domCrawler, '//description', null, $domRunnerAfterPurify);
            $htmlContentItem->addCloner(new ImageCloner());
            $categoriesItem = new TextArray('categories', $domCrawler, '//category');
            $bags[$index]->setItems($titleItem, $htmlContentItem, $categoriesItem);
            $bags[$index]->build();
        }
        return Collector::collect($bags);
    }

}