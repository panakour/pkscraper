<?php
/**
 * Created by PhpStorm.
 * User: panakour
 * Date: 3/16/19
 * Time: 9:22 AM
 */

namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Fix\ImageFromDataSrcFixer;

class ImageFromDataSrcFixerTest extends TestCase
{

    public function testFix()
    {
        $fixer = new ImageFromDataSrcFixer();
        $fixer->setDocument(file_get_contents(__DIR__ . '/fixtures/htmlDocument.html'));
        $result = $fixer->fix();
        $this->assertStringContainsString('src="image-from-data-src.png"', $result);
        $this->assertStringNotContainsString('src="data:image-from-local-data"', $result);
    }

}