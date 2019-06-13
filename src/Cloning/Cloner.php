<?php

namespace Pkscraper\Cloning;

abstract class Cloner
{
    protected $document;

    public function setDocument(string $document)
    {
        $this->document = $document;
    }

    abstract public function doClone();
}