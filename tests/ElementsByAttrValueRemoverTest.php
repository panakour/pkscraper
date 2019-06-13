<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Remove\ElementsByAttrValueRemover;

class ElementsByAttrValueRemoverTest extends TestCase
{

    public function testRemove()
    {
        $remover = new ElementsByAttrValueRemover('img', 'src', 'https://example.com/-njpg0.jpg');
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();

        $this->assertStringNotContainsString("https://example.com/-njpg0.jpg", $result);
    }
}