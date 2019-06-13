<?php

namespace Pkscraper\Clean;

/**
 * Class Cleaner
 *
 * @package Pkscraper\Cleaner
 */
abstract class Cleaner
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

    /**
     *
     * @return mixed
     */
    abstract public function clean(): string;

}