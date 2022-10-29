<?php

namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Clean\HtmlCleaner;
use Pkscraper\Clean\TextLinesCleaner;
use Pkscraper\Cloning\ImageCloner;
use Pkscraper\Cloning\ImageFromDataSrcSetCloner;
use Pkscraper\Cloning\RegexImageCloner;
use Pkscraper\Dom\DomCrawler;
use Pkscraper\Exceptions\EmptyItemException;
use Pkscraper\Fix\Fixer;
use Pkscraper\Items\Item;
use Pkscraper\Transform\Transformer;
use ReflectionClass;

class ItemTest extends TestCase
{
    /**
     * @var Item
     */
    private $item;


    protected function setUp(): void
    {
        $mockDomCrawler = $this->getMockBuilder(DomCrawler::class)->getMock();
        $this->item = new class('', $mockDomCrawler, '') extends Item
        {
            protected function doExtract()
            {
            }
        };
    }

    public function testAddCleaner()
    {
        $this->item->addCleaner(new HtmlCleaner());
        $this->item->addCleaner(new TextLinesCleaner());
        $rClass = new ReflectionClass($this->item);
        $cleaners = $rClass->getProperty('cleaners');
        $cleaners->setAccessible(true);
        $this->assertCount(2, $cleaners->getValue($this->item));
        $this->assertInstanceOf(HtmlCleaner::class, $cleaners->getValue($this->item)[0]);
        $this->assertInstanceOf(TextLinesCleaner::class, $cleaners->getValue($this->item)[1]);
    }

    public function testAddCloner()
    {
        $this->item->addCloner(new ImageCloner());
        $this->item->addCloner(new RegexImageCloner());
        $this->item->addCloner(new ImageFromDataSrcSetCloner());
        $rClass = new ReflectionClass($this->item);
        $cloners = $rClass->getProperty('cloners');
        $cloners->setAccessible(true);
        $this->assertCount(3, $cloners->getValue($this->item));
        $this->assertInstanceOf(ImageCloner::class, $cloners->getValue($this->item)[0]);
        $this->assertInstanceOf(RegexImageCloner::class, $cloners->getValue($this->item)[1]);
        $this->assertInstanceOf(ImageFromDataSrcSetCloner::class, $cloners->getValue($this->item)[2]);
    }

    public function testAddTransformer()
    {
        $this->item->addTransformer($this->getMockBuilder(Transformer::class)->getMock());
        $rClass = new ReflectionClass($this->item);
        $transformers = $rClass->getProperty('transformers');
        $transformers->setAccessible(true);
        $this->assertCount(1, $transformers->getValue($this->item));
        $this->assertInstanceOf(Transformer::class, $transformers->getValue($this->item)[0]);
    }

    public function testAddFixer()
    {
        $this->item->addFixer($this->getMockBuilder(Fixer::class)->getMock());
        $rClass = new ReflectionClass($this->item);
        $fixers = $rClass->getProperty('fixers');
        $fixers->setAccessible(true);
        $this->assertCount(1, $fixers->getValue($this->item));
        $this->assertInstanceOf(Fixer::class, $fixers->getValue($this->item)[0]);
    }

    public function testBuildEmptyItemException()
    {
        $itemMock = $this->getMockBuilder(Item::class)->disableOriginalConstructor()->setMethods(['doExtract'])->getMock();
        $this->expectException(EmptyItemException::class);
        $itemMock->expects($this->once())->method('doExtract')->with();
        $itemMock->build();
    }

//    public function testBuild()
//    {
////        $itemMock = $this->getMockBuilder(Item::class)->disableOriginalConstructor()->setMethods(['doExtract', 'doClone'])->getMock();
////        $this->expectException(EmptyItemException::class);
////        $itemMock->expects($this->at(0))->method('doExtract')->with();
////        $itemMock->build();
//    }
}