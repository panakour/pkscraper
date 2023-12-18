<?php

namespace Pkscraper\Tests;

use InvalidArgumentException;
use Panakour\DOM\Dom;
use PHPUnit\Framework\TestCase;

class DomTest extends TestCase
{
    /**
     * @var Dom
     */
    private $dom;

    protected function setUp(): void
    {
        $htmlContent = file_get_contents(__DIR__ . '/fixtures/htmlDocument.html');
        $this->dom = new Dom($htmlContent);
    }

    public function testGetAttributeValuesFromElement()
    {
        $sourceAttributesValue = $this->dom->getAttributesValue('img', 'src');

        $expectedSourceAttributes = [
            'http://www.example.com/image.jpg',
            'https://example.com/-njpg0.jpg',
            '/image-within-relative-link.jpg',
            '/hello-content-img.jpeg',
            'img_orange_flowers.jpg',
            'img_orange_flowers.jpg',
            'image_to_be_remove.jpg',
            'img_orange_flowers.jpg',
            'data:image-from-local-data',
            'https://example.com/-njpg0.jpg'
        ];
        $this->assertSame($expectedSourceAttributes, $sourceAttributesValue);
    }

    public function testReplaceAttributesOnElement()
    {
        $attributesToSet = [
            [
                'src' => 'http//kati.com',
                'alt' => 'τεστς',
                'class' => 'img-responsive main-image',
                'custom-attr' => 'value',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
            [
                'src' => 'http//kati2.com',
                'alt' => 'Alt tag 2',
                'class' => 'img-responsive-2',
            ],
        ];
        $this->dom->replaceAttributes('img', $attributesToSet);

        $this->assertStringContainsString('src="http//kati.com"', $this->dom->get());
        $this->assertStringContainsString('alt="τεστς"', $this->dom->get());
        $this->assertStringContainsString('class="img-responsive main-image"', $this->dom->get());
        $this->assertStringContainsString('src="http//kati2.com"', $this->dom->get());
        $this->assertStringContainsString('alt="Alt tag 2"', $this->dom->get());
        $this->assertStringContainsString('class="img-responsive-2"', $this->dom->get());
        $this->assertStringContainsString('custom-attr="value"', $this->dom->get());
    }

    public function testExceptionOnReplaceWrongAmountOfAttributesOnElement()
    {
        $this->expectException(InvalidArgumentException::class);
        $wrongAmountOfAttributes = [
            ['src' => 'http//kati.com', 'alt' => 'τεστς', 'class' => 'img-responsive main-image'],
            ['src' => 'http//kati2.com', 'alt' => 'Alt tag 2', 'class' => 'img-responsive-2'],
        ];
        $this->expectExceptionMessage(sprintf("Items are not equal. The number of original elements are: %d but you give %d", $this->dom->getNodeList('img')->length, count($wrongAmountOfAttributes)));
        $this->dom->replaceAttributes('img', $wrongAmountOfAttributes);
    }

    public function testRemoveElementAndItsContent()
    {
        $this->dom->removeElementsAndItsContent(['img']);
        $this->assertTrue($this->dom->getNodeList('img')->length === 0);

        $this->dom->removeElementsAndItsContent(['span']);
        $this->assertTrue($this->dom->getNodeList('span')->length === 0);

        $this->dom->removeElementsAndItsContent(['style']);
        $this->assertTrue($this->dom->getNodeList('style')->length === 0);
    }

    public function testRemoveAttribute()
    {
        $this->dom->removeAttribute('width');
        $this->assertFalse($this->dom->getNodeList('img')->item(0)->hasAttribute('width'));
        $this->assertFalse($this->dom->getNodeList('img')->item(1)->hasAttribute('width'));
        $this->dom->removeAttribute('height');
        $this->assertFalse($this->dom->getNodeList('img')->item(0)->hasAttribute('height'));
        $this->assertFalse($this->dom->getNodeList('img')->item(1)->hasAttribute('height'));
        $this->dom->removeAttribute('style');
        $this->assertFalse($this->dom->getNodeList('p')->item(0)->hasAttribute('style'));
    }

    public function testWrapDocument()
    {
        $this->dom->wrapDocument('custom-element', 'custom-class');
        $this->assertStringContainsString('custom-element', $this->dom->getTextContent());
        $this->assertStringContainsString('custom-class', $this->dom->getTextContent());
    }

    public function testRemoveAllAttributesExcept()
    {
        $this->dom->removeAllAttributesExcept([
            'width',
            'src',
        ]);
        $this->assertStringContainsString('src', $this->dom->get());
        $this->assertStringContainsString('width', $this->dom->get());
        $this->assertStringNotContainsString('alt', $this->dom->get());
    }

    public function testRemoveAllAttribute()
    {
        $this->dom->removeAllAttributes();
        $this->assertStringNotContainsString('src', $this->dom->get());
        $this->assertStringNotContainsString('width', $this->dom->get());
        $this->assertStringNotContainsString('height', $this->dom->get());
    }

    public function testReplaceElement()
    {
        $img = $this->dom->DOMDocument->createElement('my-element');
        $img->setAttribute('class', 'my-class');
        $elementToReplace = $this->dom->DOMDocument->getElementById('example-image-first');
        $this->dom->replaceElement($elementToReplace, $img);
        $this->assertStringNotContainsString('example-image-first', $this->dom->get());
        $this->assertStringContainsString('my-element', $this->dom->get());
        $this->assertStringContainsString('my-class', $this->dom->get());
    }

    public function testRemoveElementById()
    {
        $id = "example-image-first";
        $this->dom->removeElementById($id);
        $this->assertStringNotContainsString('example-image-first', $this->dom->get());
    }

    public function testRemoveElementsByClass()
    {
        $class = "mt20";
        $this->dom->removeElementsByClass($class);
        $this->assertStringNotContainsString('mt20', $this->dom->get());
    }

    public function testSetAttributes()
    {
        $this->dom->setAttributes('a', ['target' => '_blank', 'rel' => 'nofollow']);
        $this->assertStringContainsString('target="_blank"', $this->dom->get());
        $this->assertStringContainsString('rel="nofollow"', $this->dom->get());
    }
}