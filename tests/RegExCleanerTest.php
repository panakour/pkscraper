<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Clean\RegExCleaner;
use Pkscraper\Dom\Dom;

class RegExCleanerTest extends TestCase
{

    public function testCleanAllImgTag()
    {
        $cleaner = new RegExCleaner('/<\\/?img(\\s+.*?>|>)/', '');
        $cleaner->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $cleaner->clean();
        $dom = new Dom($result);
        $this->assertEquals(0, $dom->getNodeList('img')->length);
    }

    public function testCleanAllATag()
    {
        $cleaner = new RegExCleaner('/<\\/?a(\\s+.*?>|>)/', '');
        $cleaner->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $cleaner->clean();
        $dom = new Dom($result);
        $this->assertEquals(0, $dom->getNodeList('a')->length);
    }

    public function testCleanFirstParagraph()
    {
        $cleaner = new RegExCleaner('/<\\/?p(\\s+.*?>|>)(?1)/', 'replacement paragraph content');
        $cleaner->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $cleaner->clean();
        $this->assertStringNotContainsString('first paragraph content', $result);
        $this->assertStringContainsString('replacement paragraph content', $result);
    }
}