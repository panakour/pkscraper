<?php


namespace Pkscraper\Tests;



use PHPUnit\Framework\TestCase;
use Pkscraper\Remove\ElementByTagByIndexRemover;

class ElementByTagByIndexRemoverTest extends TestCase
{

    public function testRemove()
    {
        $remover = new ElementByTagByIndexRemover('img', 1);
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('this is the second image tag', $result);
    }

}