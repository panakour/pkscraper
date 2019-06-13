<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Exceptions\ElementNotFoundException;
use Pkscraper\Remove\ElementByIdRemover;

class ElementsByIdRemoverTest extends TestCase
{

    public function testRemove()
    {
        $remover = new ElementByIdRemover('example-image-first');
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('example-image-first', $result);

        $remover = new ElementByIdRemover(['copy', 'customtag']);
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('Lorem Ipsum', $result);
        $this->assertStringNotContainsString('customtag', $result);
    }

    public function testRemoveThrowElementNotFoundException()
    {
        $this->expectException(ElementNotFoundException::class);
        $remover = new ElementByIdRemover('this-id-does-not-exist');
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $remover->remove();
    }

}