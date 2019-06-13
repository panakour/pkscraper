<?php

namespace Pkscraper\Tests;

use PHPUnit\Framework\TestCase;
use Pkscraper\Clean\TextLinesCleaner;

class TextLinesCleanerTest extends TestCase
{
    public function testClean()
    {
        $textCleaner = new TextLinesCleaner();
        $textCleaner->setDocument('Hello\n \ttext\n\t\r Hello \rtext');
        $document = $textCleaner->clean();
        $this->assertEquals('Hello text Hello text', $document);
    }
}