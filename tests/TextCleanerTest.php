<?php

namespace Pkscraper\Tests;

use PHPUnit\Framework\TestCase;
use Pkscraper\Clean\TextCleaner;

class TextCleanerTest extends TestCase
{
    public function testClean()
    {
        $textCleaner = new TextCleaner("text to remove ", "");
        $textCleaner->setDocument('Hello test. this is the text to remove end.');
        $document = $textCleaner->clean();
        $this->assertEquals('Hello test. this is the end.', $document);

        $textCleaner = new TextCleaner("text to remove ", "text to replace ");
        $textCleaner->setDocument('Hello test. this is the text to remove end.');
        $document = $textCleaner->clean();
        $this->assertEquals('Hello test. this is the text to replace end.', $document);
    }
}