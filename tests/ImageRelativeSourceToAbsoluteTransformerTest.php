<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Transform\ImageRelativeSourceToAbsoluteTransformer;

class ImageRelativeSourceToAbsoluteTransformerTest extends TestCase
{


    public function testTransform()
    {
        $transformer = new ImageRelativeSourceToAbsoluteTransformer('http://example.com/');
        $transformer->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $transformer->transform();
        $this->assertStringContainsString('http://example.com/img_orange_flowers.jpg', $result);
        $this->assertStringContainsString('http://example.com//hello-content-img.jpeg', $result);
    }
}