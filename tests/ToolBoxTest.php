<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\ToolBox;

class ToolBoxTest extends TestCase
{

    public function testGetUrlWithoutPath()
    {
        $result = ToolBox::getUrlWithoutPath('https://www.php.net/manual/en/function.parse-url.php');
        $this->assertEquals('https://www.php.net/', $result);

        $result = ToolBox::getUrlWithoutPath('http://example.com/?test=test&hellow=test');
        $this->assertEquals('http://example.com/', $result);
    }
}