<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Remove\ElementsByClassRemover;

class ElementsByClassRemoverTest extends TestCase
{

    public function testRemove()
    {
        $remover = new ElementsByClassRemover('element-to-be-remove');
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('element-to-be-remove', $result);
        $this->assertStringNotContainsString('Content to Be Remove', $result);
        $this->assertStringNotContainsString('image_to_be_remove.jpg', $result);

        $remover = new ElementsByClassRemover(['element-to-be-remove', 'multiremover']);
        $remover->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $remover->remove();
        $this->assertStringNotContainsString('element-to-be-remove', $result);
        $this->assertStringNotContainsString('multiremover', $result);
        $this->assertStringNotContainsString('mb10', $result);
    }

}