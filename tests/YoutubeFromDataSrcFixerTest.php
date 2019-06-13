<?php
/**
 * Created by PhpStorm.
 * User: panakour
 * Date: 3/16/19
 * Time: 10:34 AM
 */

namespace Pkscraper\Tests;


use PHPUnit\Framework\TestCase;
use Pkscraper\Fix\YoutubeFromDataSrcFixer;

class YoutubeFromDataSrcFixerTest extends TestCase
{

    const DOC = '
    <picture >
            <img class="lazyload" src="https://example.com/images/963x541/files/system/thema.jpg" data-src="https://example.com/images/963x541/files/YouTube/Uf5-8pY0pxM.jpg" />
    </picture>
    <picture >
            <img class="lazyload" src="https://example.com/images/963x541/files/system/thema.jpg" data-src="https://example.com/images/963x541/files/YouTube/bZYBd6tfjpY.jpg" />
    </picture>
    <picture >
            <img class="lazyload" src="https://example.com/images/963x541/files/system/thema.jpg" data-src="https://example.com/images/963x541/files/YouTube/lc-qLEnXviM.jpg" />
    </picture>
    <picture >
            <img class="lazyload" src="https://example.com/images/963x541/files/system/thema.jpg" data-src="https://example.com/images/963x541/files/YouTube/last_youtube_id.jpg" />
    </picture>';

    public function testFix()
    {
        $obj = new YoutubeFromDataSrcFixer();
        $obj->setDocument(self::DOC);
        $result = $obj->fix();
        $dataSet = [
            '<iframe src="https://www.youtube.com/embed/Uf5-8pY0pxM"></iframe>',
            '<iframe src="https://www.youtube.com/embed/bZYBd6tfjpY"></iframe>',
            '<iframe src="https://www.youtube.com/embed/lc-qLEnXviM"></iframe>',
            '<iframe src="https://www.youtube.com/embed/last_youtube_id"></iframe>',
        ];
        foreach ($dataSet as $data) {
            $this->assertStringContainsString($data, $result);
        }
    }
}