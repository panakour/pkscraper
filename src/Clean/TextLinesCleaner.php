<?php

namespace Pkscraper\Clean;

class TextLinesCleaner extends Cleaner
{
    public function clean(): string
    {
        $this->removeLines();

        return $this->document;
    }

    protected function removeLines(): void
    {
        $this->document = str_replace(['\r', '\n', '\t'], '', $this->document);
    }
}