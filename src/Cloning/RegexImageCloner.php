<?php


namespace Pkscraper\Cloning;


class RegexImageCloner extends Cloner
{

    public function doClone()
    {
        preg_match_all('/<img[^>]+src="([^">]+)"/', $this->document, $match);
        return $match[1];
    }

}