<?php

namespace Pkscraper\Cloning;

use Panakour\DOM\Dom;

class ImageFromDataSrcSetCloner extends Cloner
{
    public function doClone()
    {
        $dom = new Dom($this->document);

        $images = $dom->getAttributesValue('img', 'src');

        foreach ($images as $key => $image) {
            if (substr($image, 0, 5) === 'data:') {
                $images[$key] = $dom->getAttributesValue('img', 'data-srcset')[$key];
            }
        }

        return $images;

    }
}