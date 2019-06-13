<?php


namespace Pkscraper\Remove;


abstract class Remover
{

    /**
     * @var string
     */
    protected $document;

    /**
     * @param string $document
     */
    public function setDocument(string $document)
    {
        $this->document = $document;
    }

    abstract public function remove(): string;

}