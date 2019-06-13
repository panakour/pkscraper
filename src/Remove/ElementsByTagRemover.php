<?php


namespace Pkscraper\Remove;


use Pkscraper\Dom\Dom;

class ElementsByTagRemover extends Remover
{

    protected $tag;

    /**
     * ElementsByTagRemover constructor.
     * @param string|array $tag
     */
    public function __construct($tag)
    {
        $this->tag = (array)$tag;
    }

    public function remove(): string
    {
        $dom = new Dom($this->document);

        foreach ($this->tag as $tagItem) {
            $elementsToBeRemove = $dom->getNodeList($tagItem);
            foreach ($elementsToBeRemove as $e) {
                $e->parentNode->removeChild($e);
            }
        }

        return $dom->get();
    }
}