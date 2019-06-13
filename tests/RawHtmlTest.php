<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Dom\DomCrawler;
use Pkscraper\Exceptions\EmptyItemException;
use Pkscraper\Items\RawHtml;

class RawHtmlTest extends TestCase
{

    public function testThrowEmptyItemException()
    {
        $this->expectException(EmptyItemException::class);
        $mockDomCrawler = $this->getMockBuilder(DomCrawler::class)->getMock();
        $rawHtml = new RawHtml('my html item', $mockDomCrawler, '//xpath-selector');
        $rawHtml->build();
    }
}