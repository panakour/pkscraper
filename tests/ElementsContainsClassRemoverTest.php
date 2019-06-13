<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Remove\ElementsContainsClassRemover;

class ElementsContainsClassRemoverTest extends TestCase
{

    public function testRemove()
    {
        $remover = new ElementsContainsClassRemover('col-');
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('col-md-6', $result);
        $this->assertStringNotContainsString('col-md-12', $result);
    }

}