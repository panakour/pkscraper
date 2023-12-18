<?php


namespace Pkscraper\Fix;


use Panakour\DOM\Dom;

class YoutubeFromDataSrcFixer extends Fixer
{

    public function fix(): string
    {
        //currently only working for websites that has data-src to youtube value and "YouTube" path to url.
        $dom = new Dom($this->document);
        $attrValues = $dom->getAttributesValue('img', 'data-src');
        $attrValuesLength = count($attrValues);
        $attrIndex = 0;
        for ($i = 0; $i < $attrValuesLength; $i++) {
            $paths = \Pkscraper\ToolBox::getUrlPathComponents($attrValues[$attrIndex]);

            if (isset($paths[3]) && $paths[3] === "YouTube") {
                $youtubeId = substr($paths[4], 0, -4);

                $iframe = $dom->DOMDocument->createElement('iframe');
                $iframe->setAttribute('src', "https://www.youtube.com/embed/$youtubeId");

                $elementToBeReplaced = $dom->getNodeList('img')->item($i);
                $dom->replaceElement($elementToBeReplaced, $iframe);
                $i -= 1;
                $attrValuesLength -= 1;
                $attrIndex++;
            }

        }
        return $dom->get();
    }
}