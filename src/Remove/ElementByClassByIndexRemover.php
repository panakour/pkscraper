<?php


namespace Pkscraper\Remove;


use DOMXPath;
use Panakour\DOM\Dom;

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
        $elementToBeRemove = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " ' . $this->class . ' ")]')->item($this->index);
        if ($elementToBeRemove !== null) {
            $elementToBeRemove->parentNode->removeChild($elementToBeRemove);
        }
        return $dom->get();
    }
}