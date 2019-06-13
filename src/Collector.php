<?php

namespace Pkscraper;

use Pkscraper\Bag;

class Collector
{
    /**
     * @param Bag[] $bags
     * @return array
     */
    public static function collect(array $bags): array
    {
        $collectedData = [];
        foreach ($bags as $index => $bag) {
            $collectedData[$index]['origin'] = $bag->getOrigin();
            foreach ($bag->getItems() as $item) {
                $collectedData[$index][$item->getCode()]['extractedValue'] = $item->getExtractedValue();
                if (count($item->getCloners())) {
                    $collectedData[$index][$item->getCode()]['clonedValue'] = $item->getClonedValue();
                }
            }
        }

        return $collectedData;
    }


}