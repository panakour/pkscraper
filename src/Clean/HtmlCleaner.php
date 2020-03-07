<?php

namespace Pkscraper\Clean;

use Closure;
use HTMLPurifier;
use HTMLPurifier_Config;
use Pkscraper\Dom\Dom;
use Pkscraper\ToolBox;

class HtmlCleaner extends Cleaner
{


    /**
     * @var Closure
     */
    private $domRunnerBeforePurify;

    /**
     * @var Closure
     */
    private $domRunnerAfterPurify;

    private $allowedElements = [
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

    private $allowedAttributes = [
        'img.src',
        'blockquote.class',
    ];

    private $allowedClasses = [
        'twitter-tweet',
    ];

    /**
     * @var Dom
     */
    private $dom;

    public function clean(): string
    {
        $this->dom = new Dom($this->document);

        $this->runDomBeforePurify();
        $this->purify();
        $this->runDomAfterPurify();

        return $this->dom->get();
    }

    public function setAllowedElements(array $allowedElements)
    {
        $this->allowedElements = $allowedElements;
    }

    protected function purify(): void
    {
        $purifierConfig = HTMLPurifier_Config::createDefault();
        $this->enableIframeForYoutubeVimeoFacebook($purifierConfig);
        $purifierConfig->set('HTML.AllowedElements', $this->allowedElements);
        if (in_array('a', $this->allowedElements)) {
            $this->allowedAttributes = array_merge($this->allowedAttributes, ['a.href', 'a.target', 'a.rel']);
        }
        $purifierConfig->set('HTML.AllowedAttributes', $this->allowedAttributes);
        $purifierConfig->set('Attr.AllowedClasses', $this->allowedClasses);
        $purifierConfig->set('Attr.AllowedFrameTargets', ['_blank']);
        $purifierConfig->set('Attr.AllowedRel', ['nofollow']);
        $purifierConfig->set('HTML.Nofollow', true);
        $purifierConfig->set('Output.Newline', "");
        $purifierConfig->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
        $purifierConfig->set('AutoFormat.RemoveEmpty', true);
        $filter = new HTMLPurifier($purifierConfig);
        $this->dom = new Dom($filter->purify($this->dom->get()));
        $this->enableIframeFullScreen(); //should be placed after HTMLPurifier because HTMLPurifier will remove these attributes
        $this->cleanRelativeUrls();
    }

    public function cleanRelativeUrls()
    {
        $links = $this->dom->getNodeList('a');
        for ($i = 0; $i < $links->length; $i++) {
            $href = $links->item($i)->getAttribute('href');
            if (ToolBox::isRelativeUrl($href)) {
                if ($links->item($i)->nodeValue !== "") {
                    $span = $this->dom->DOMDocument->createElement('span', $links->item($i)->nodeValue);
                    $this->dom->replaceElement($links->item($i), $span);
                } elseif ($links->item($i)->getElementsByTagName('img')->length !== 0) {
                    //in case of none nodeValue expect that image exist on child element
                    $this->dom->replaceElement($links->item($i), $links->item($i)->getElementsByTagName('img')[0]);
                }
                $i -= 1;
            }
        }
    }

    protected function enableIframeForYoutubeVimeoFacebook($purifierConfig)
    {
        if ($this->dom->getNodeList('iframe')->length) {
            array_push($this->allowedElements, 'iframe');
            array_push($this->allowedAttributes, 'iframe.src');
            $purifierConfig->set('HTML.SafeIframe', true);
            $purifierConfig->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/|www\.facebook\.com/plugins/video.php\?)%');
        }
    }

    protected function runDomBeforePurify()
    {
        $this->runDom($this->domRunnerBeforePurify);
    }

    protected function runDomAfterPurify()
    {
        $this->runDom($this->domRunnerAfterPurify);
    }

    protected function runDom(?Closure $domRunner)
    {
        if ($domRunner) {
            $domRunner->call($this->dom);
        }
    }

    protected function enableIframeFullScreen()
    {
        $allBrowserAttributes = [
            'allowfullscreen' => 'allowfullscreen',
            'mozallowfullscreen' => 'mozallowfullscreen',
            'msallowfullscreen' => 'msallowfullscreen',
            'oallowfullscreen' => 'oallowfullscreen',
            'webkitallowfullscreen' => 'webkitallowfullscreen',
        ];
        $this->dom->setAttributes('iframe', $allBrowserAttributes);
    }

    public function useDomBeforePurify(?Closure $domRunner)
    {
        $this->domRunnerBeforePurify = $domRunner;
    }

    public function useDomAfterPurify(?Closure $domRunner)
    {
        $this->domRunnerAfterPurify = $domRunner;
    }

}
