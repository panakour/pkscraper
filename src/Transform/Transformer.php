<?php

namespace Pkscraper\Transform;

abstract class Transformer
{
    protected $document;

    /**
     * @param mixed $document
     */
    public function setDocument($document): void
    {
        $this->document = $document;
    }

    abstract public function transform();
}