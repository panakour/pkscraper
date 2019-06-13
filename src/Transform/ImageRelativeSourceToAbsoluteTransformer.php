<?php


namespace Pkscraper\Transform;


use Pkscraper\Dom\Dom;

class ImageRelativeSourceToAbsoluteTransformer extends Transformer
{

    protected $prefix;

    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function transform()
    {
        $dom = new Dom($this->document);
        $dom->replaceImagesSourceToAbsolute($this->prefix);
        return $dom->get();
    }
}