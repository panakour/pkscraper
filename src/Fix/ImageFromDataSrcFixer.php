<?php
/**
 * Created by PhpStorm.
 * User: panakour
 * Date: 3/14/19
 * Time: 10:46 PM
 */

namespace Pkscraper\Fix;


use Pkscraper\Dom\Dom;

class ImageFromDataSrcFixer extends Fixer
{

    //this will help to take the real image from data src that is lazy loaded and put it to src attribute of image tag
    public function fix(): string
    {
        $dom = new Dom($this->document);
        $images = $dom->getNodeList('img');
        foreach ($images as $key => $image) {
            $image->setAttribute('src', $image->getAttribute('data-src'));
        }

        return $dom->get();
    }

}