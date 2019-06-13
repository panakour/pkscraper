<?php


namespace Pkscraper\Items;

use Closure;
use Pkscraper\Clean\HtmlCleaner;
use Pkscraper\Dom\DomCrawler;

class SafeHtml extends Item
{

    const ALLOWED_ELEMENTS = [
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
//        'div',
        'a',
        'em',
        'strong',
        'b',
        'cite',
        'blockquote',
        'ul',
        'ol',
        'li',
        'dl',
        'dt',
        'dd',
        'img',
        'br',
        'p',
        'center',
//        'span',
        'table',
        'thead',
        'tbody',
        'td',
        'th',
        'tr',
        'sub',
        'sup',
    ];

    /**
     * SpecialHtmlString constructor.
     *
     * @param string $code
     * @param DomCrawler $domCrawler
     * @param string $selector
     * @param array $allowedElements
     * @param Closure|null $domRunnerBeforePurify
     * @param Closure|null $domRunnerAfterPurify
     */
    public function __construct(
        string $code,
        DomCrawler $domCrawler,
        string $selector,
        array $allowedElements = self::ALLOWED_ELEMENTS,
        Closure $domRunnerBeforePurify = null,
        Closure $domRunnerAfterPurify = null
    )
    {
        parent::__construct($code, $domCrawler, $selector);
        $this->setupHtmlCleaner($allowedElements, $domRunnerBeforePurify, $domRunnerAfterPurify);
    }

    protected function doExtract()
    {
        $this->extractedValue = $this->originalExtractedValue = htmlspecialchars_decode($this->domCrawler->extractHtmlByXpath($this->selector));
    }

    protected function setupHtmlCleaner($allowedElements, $domRunnerBeforePurify, $domRunnerAfterPurify)
    {
        $htmlCleaner = new HtmlCleaner();
        $htmlCleaner->setAllowedElements($allowedElements);
        $htmlCleaner->useDomBeforePurify($domRunnerBeforePurify);
        $htmlCleaner->useDomAfterPurify($domRunnerAfterPurify);
        $this->cleaners[] = $htmlCleaner;
    }

}