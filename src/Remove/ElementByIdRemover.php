<?php


namespace Pkscraper\Remove;


use Pkscraper\Dom\Dom;
use Pkscraper\Exceptions\ElementNotFoundException;

class ElementByIdRemover extends Remover
{

    protected $id;

    /**
     * ElementByIdRemover constructor.
     * @param string|array $id
     */
    public function __construct($id)
    {
        $this->id = (array)$id;
    }


    /**
     * @throws ElementNotFoundException
     */

    public function remove(): string
    {
        $dom = new Dom($this->document);

        foreach ($this->id as $id) {
            $elementToBeRemove = $dom->DOMDocument->getElementById($id);
            if ($elementToBeRemove === null) {
                throw new ElementNotFoundException("Element with id:" . $id . ' does not exist.');
            }
            $elementToBeRemove->parentNode->removeChild($elementToBeRemove);
        }

        return $dom->get();
    }
}