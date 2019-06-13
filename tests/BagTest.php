<?php

namespace Pkscraper\Tests;

use PHPUnit\Framework\TestCase;
use Pkscraper\Bag;
use Pkscraper\Dom\DomCrawler;
use Pkscraper\Items\HtmlArray;
use Pkscraper\Items\RawHtml;
use Pkscraper\Items\SafeHtml;
use Pkscraper\Items\Text;
use Pkscraper\Items\TextArray;

class BagTest extends TestCase
{

    private $htmlArr;
    private $text;
    private $textArr;
    private $rawHtml;
    private $safeHtml;

    protected function setUp(): void
    {
        parent::setUp();

        $this->text = $this->getMockBuilder(Text::class)->disableOriginalConstructor()->getMock();
        $this->textArr = $this->getMockBuilder(TextArray::class)->disableOriginalConstructor()->getMock();
        $this->rawHtml = $this->getMockBuilder(RawHtml::class)->disableOriginalConstructor()->getMock();
        $this->safeHtml = $this->getMockBuilder(SafeHtml::class)->disableOriginalConstructor()->getMock();
        $this->htmlArr = $this->getMockBuilder(HtmlArray::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetOrigin()
    {
        $bag = new Bag("http://example.com/mytestorigin");
        $this->assertEquals("http://example.com/mytestorigin", $bag->getOrigin());
    }

    public function testSetItem()
    {
        $bag = new Bag("http://example.com/...");
        $bag->setItem($this->text);
        $bag->setItem($this->textArr);
        $bag->setItem($this->rawHtml);
        $bag->setItem($this->safeHtml);
        $bag->setItem($this->htmlArr);
        $this->assertCount(5, $bag->getItems());
    }

    public function testSetItems()
    {
        $bag = new Bag("http://example.com/...");
        $bag->setItems($this->text, $this->rawHtml);
        $bag->setItems($this->safeHtml, $this->htmlArr, $this->textArr);
        $bag->setItems($this->htmlArr);
        $this->assertCount(1, $bag->getItems());
    }

    public function testGetItemByName()
    {
        $bag = new Bag("http://example.com/...");
        $crawler = $this->getMockBuilder(DomCrawler::class)->getMock();
        $bag->setItem(new TextArray("textArr", $crawler, "selector"));
        $bag->setItem(new RawHtml("rawHtml", $crawler, "selector"));
        $bag->setItem(new HtmlArray("htmlArr", $crawler, "selector"));
        $bag->setItem(new Text("title", $crawler, "selector"));
        $bag->setItem($this->textArr);
        $bag->setItem($this->rawHtml);
        $bag->setItem($this->safeHtml);
        $bag->setItem($this->htmlArr);
        $this->assertNull($bag->getItemByCode("notFoundName"));
        $titleItem = $bag->getItemByCode("title");
        $this->assertEquals("title", $titleItem->getCode());
        $rawHtmlItem = $bag->getItemByCode("rawHtml");
        $this->assertEquals("rawHtml", $rawHtmlItem->getCode());
    }
}