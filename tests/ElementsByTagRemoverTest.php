<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Remove\ElementsByTagRemover;

class ElementsByTagRemoverTest extends TestCase
{

    public function testRemove()
    {
        $remover = new ElementsByTagRemover('tagtoremove');
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('tagtoremove', $result);

        $remover = new ElementsByTagRemover(['tagtoremove', 'customtag']);
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('tagtoremove', $result);
        $this->assertStringNotContainsString('customtag', $result);
        $this->assertStringNotContainsString('custom tag', $result);

    }

}