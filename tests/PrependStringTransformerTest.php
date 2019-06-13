<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Transform\PrependStringTransformer;

class PrependStringTransformerTest extends TestCase
{

    public function testTransform()
    {
        $document = " hello test";
        $prependString = "prepend me";
        $transformer = new PrependStringTransformer($prependString);
        $transformer->setDocument($document);
        $result = $transformer->transform();
        $this->assertEquals($prependString.$document, $result);
    }

}