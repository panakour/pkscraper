<?php


namespace Pkscraper\Clean;


class RegExCleaner extends Cleaner
{

    protected $pattern;
    protected $replacement;

    public function __construct($pattern, $replacement)
    {
        $this->pattern = $pattern;
        $this->replacement = $replacement;
    }

    public function clean(): string
    {
        return preg_replace($this->pattern, $this->replacement, $this->document);
    }
}