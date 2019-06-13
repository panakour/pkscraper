<?php
/**
 * Created by PhpStorm.
 * User: panakour
 * Date: 2/10/19
 * Time: 11:21 PM
 */

namespace Pkscraper\Fix;


abstract class Fixer
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

    abstract public function fix(): string;

}