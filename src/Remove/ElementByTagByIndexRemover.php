<?php


namespace Pkscraper\Remove;


use Panakour\DOM\Dom;

class ElementByTagByIndexRemover extends Remover
{

    protected $tag;
    protected $index;

    /**
     * ElementByTagByIndexRemover constructor.
     * @param string $tag
     * @param int $index
     */
    public function __construct($tag, int $index)
    {
        $this->tag = $tag;
        $this->index = $index;
    }

    public function remove(): string
    {
        $dom = new Dom($this->document);

        $elementToBeRemove = $dom->getNodeList($this->tag)->item($this->index);
        if ($elementToBeRemove !== null) {
            $elementToBeRemove->parentNode->removeChild($elementToBeRemove);
        }

        return $dom->get();
    }
}