<?php

namespace Pkscraper\Cloning;

use Pkscraper\Dom\Dom;

class ImageCloner extends Cloner
{
    public function doClone()
    {
        $dom = new Dom($this->document);

        return $dom->getAttributesValue('img', 'src');
    }
}