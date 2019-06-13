<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Cloning\RegexImageCloner;

class RegexImageClonerTest extends TestCase
{

    public function testExtractImageSources()
    {
        $cloner = new RegexImageCloner();
        $cloner->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $expected = [
            "http://www.example.com/image.jpg",
            "https://example.com/-njpg0.jpg",
            "/image-within-relative-link.jpg",
            "/hello-content-img.jpeg",
            "img_orange_flowers.jpg",
            "img_orange_flowers.jpg",
            "image_to_be_remove.jpg",
            "img_orange_flowers.jpg",
            'image-from-data-src.png',
            'https://example.com/-njpg0.jpg',

        ];
        $this->assertEquals($expected, $cloner->doClone());
    }

}