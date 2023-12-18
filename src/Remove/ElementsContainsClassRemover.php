<?php


namespace Pkscraper\Remove;


use DOMXPath;
use Panakour\DOM\Dom;

class ElementsContainsClassRemover extends Remover
{
    protected $class;

    /**
     * ElementsContainsClassRemover constructor.
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
        foreach ($this->class as $classItem) {
            $elementsToBeRemove = $xpath->query('//*[contains(attribute::class, "' . $classItem . '")]');
            foreach ($elementsToBeRemove as $e) {
                $e->parentNode->removeChild($e);
            }
        }
        return $dom->get();

    }
}