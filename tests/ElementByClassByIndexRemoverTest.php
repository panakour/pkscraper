<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Remove\ElementByClassByIndexRemover;

class ElementByClassByIndexRemoverTest extends TestCase
{

    public function testRemove()
    {
        $remover = new ElementByClassByIndexRemover('column', 0);
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('first column', $result);
        $this->assertStringContainsString('second column', $result);

        $remover = new ElementByClassByIndexRemover('column', 1);
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringContainsString('first column', $result);
        $this->assertStringNotContainsString('second column', $result);
    }

}