<?php

namespace Pkscraper\Clean;

class TextCleaner extends Cleaner
{

    /**
     * @var string
     */
    protected $textToRemove;
    /**
     * @var string
     */
    private $textToReplace;

    /**
     * TextCleaner constructor.
     * @param string $textToRemove
     * @param string $textToReplace
     */
    public function __construct(string $textToRemove, string $textToReplace)
    {
        $this->textToRemove = $textToRemove;
        $this->textToReplace = $textToReplace;
    }

    public function clean(): string
    {
        return str_replace($this->textToRemove, $this->textToReplace, $this->document);
    }

}