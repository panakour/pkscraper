<?php


namespace Pkscraper\Remove;


use DOMXPath;
use Pkscraper\Dom\Dom;

class ElementsByAttrValueRemover extends Remover
{
    protected $element;
    protected $attr;
    protected $attrValue;


    public function __construct($element, $attr, $attrValue)
    {
        $this->element = $element;
        $this->attrValue = $attrValue;
        $this->attr = $attr;
    }

    public function remove(): string
    {
        $dom = new Dom($this->document);
        $xpath = new DomXpath($dom->DOMDocument);
        foreach ($xpath->query('//' . $this->element . '[@' . $this->attr . '="' . $this->attrValue . '"]') as $node) {
            $node->parentNode->removeChild($node);
        }

        return $dom->get();
    }
}