<?php


namespace Pkscraper\Tests;

use PHPUnit\Framework\TestCase;
use Pkscraper\Transform\AppendStringTransformer;

class AppendStringTransformerTest extends TestCase
{

    public function testTransform()
    {
        $document = " hello test";
        $appendString = "append me";
        $transformer = new AppendStringTransformer($appendString);
        $transformer->setDocument($document);
        $result = $transformer->transform();
        $this->assertEquals($document.$appendString, $result);
    }

}