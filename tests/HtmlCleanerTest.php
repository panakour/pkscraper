<?php


namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Clean\HtmlCleaner;

class HtmlCleanerTest extends TestCase
{
    /**
     * @var HtmlCleaner
     */
    public $htmlCleaner;

    protected function setUp(): void
    {
        $htmlContent = file_get_contents(__DIR__.'/fixtures/htmlDocument.html');
        $this->htmlCleaner = new HtmlCleaner();
        $this->htmlCleaner->setDocument($htmlContent);
    }

    public function testClean()
    {
        $cleanDoc = $this->htmlCleaner->clean();
        $this->assertStringNotContainsString("</div>", $cleanDoc);
        $this->assertStringNotContainsString("class", $cleanDoc);
        $this->assertStringNotContainsString("style", $cleanDoc);
        $this->assertStringNotContainsString("script", $cleanDoc);
        $this->assertStringContainsString("<span>Read more</span>", $cleanDoc);
        $this->assertStringContainsString("<span>Second read more from relative path</span>", $cleanDoc);
        $this->assertStringContainsString("<span>3 read more from relative path</span>", $cleanDoc);
        $this->assertStringNotContainsString('<a href="/relative-link-with-img"><img src="/image-within-relative-link.jpg" alt="image-within-relative-link.jpg"></a>', $cleanDoc);
        $this->assertStringContainsString('<img src="/image-within-relative-link.jpg" alt="image-within-relative-link.jpg">', $cleanDoc);
    }

    public function testCleanWithDomRunner()
    {

        $this->htmlCleaner->useDomBeforePurify(
            function () {
                $this->setAttributes('a', ['target' => '_blank', 'rel' => 'nofollow']);
            }
        );
        $cleanDoc = $this->htmlCleaner->clean();
        $this->assertStringContainsString('target="_blank"', $cleanDoc);
        $this->assertStringContainsString('nofollow', $cleanDoc);
    }

    public function testThrowExceptionWithEmptyDocument()
    {
        $this->expectException("\Pkscraper\Exceptions\EmptyDocument");
        $this->htmlCleaner->setDocument('');
        $this->htmlCleaner->clean();
    }

}