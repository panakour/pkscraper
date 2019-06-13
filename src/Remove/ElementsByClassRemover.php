<?php


namespace Pkscraper\Remove;


use DOMXPath;
use Pkscraper\Dom\Dom;

class ElementsByClassRemover extends Remover
{

    protected $class;

    /**
     * ElementsByClassRemover constructor.
     * @param string|array $class
     */
    public function __construct($class)
    {
        $this->class = (array)$class;
    }

    public function remove(): string
    {
        $dom = new Dom($this->document);

        $xpath = new DOMXPath($dom->DOMDocument);
        foreach ($this->class as $className) {
            $elementsToBeRemove = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " ' . $className . ' ")]');
            foreach ($elementsToBeRemove as $e) {
                $e->parentNode->removeChild($e);
            }
        }
        return $dom->get();
    }
}