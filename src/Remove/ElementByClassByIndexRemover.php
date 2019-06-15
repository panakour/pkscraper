<?php


namespace Pkscraper\Remove;


use DOMXPath;
use Pkscraper\Dom\Dom;

class ElementByClassByIndexRemover extends Remover
{

    protected $class;
    protected $index;

    /**
     * ElementByClassByIndexRemover constructor.
     * @param string $class
     * @param int $index
     */
    public function __construct($class, $index)
    {
        $this->class = $class;
        $this->index = $index;
    }

    public function remove(): string
    {
        $dom = new Dom($this->document);
        $xpath = new DOMXPath($dom->DOMDocument);
        $elementsToBeRemove = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " ' . $this->class . ' ")]');
        $elementsToBeRemove->item($this->index)->parentNode->removeChild($elementsToBeRemove->item($this->index));
        return $dom->get();
    }
}