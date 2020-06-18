[![Build Status](https://travis-ci.org/panakour/pkscraper.svg?branch=master)](https://travis-ci.org/panakour/pkscraper)

 ## Installation
 `composer require panakour/pkscraper`

## Examples

### Create http client with proxy and headers
```php
$httpClient = new \Pkscraper\Http\GuzzleClient();
$httpClient->setProxy('socks5://172.17.0.1:9050', 'socks5://172.17.0.1:9050');
$httpClient->setHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36']);
$httpClient->newClient();
```

### Get a text from single url
```php

$resp = $httpClient->doGetRequest("https://example.com/");
$con = new Text("img", new SymfonyDomCrawler($resp->getBody()->getContents()), "//meta[@property='og:image']/@content");
$con->build();
\Pkscraper\ToolBox::debugResult($con->getExtractedValue());
```

### Concurrent requests and group multiple fields
```php
$urls = UrlExtractor::extract($httpClient, 'https://www.example.com/feed', "//item/link");

$pool = $httpClient->concurrentRequests($urls);
foreach ($pool as $index => $response) {
    if ($response instanceof \GuzzleHttp\Exception\RequestException) {
        dd('something went wrong');
    }
    $domCrawler = new SymfonyDomCrawler($response->getBody()->getContents());
    $bags[$index] = new Bag($urls[$index]);
    $titleItem = new Text('title', $domCrawler, "//article/div[@class='box']/h2/a");
    $featuredImage = new Text('featuredImage', $domCrawler, '//meta[@property="og:image"]/@content');
    $htmlContentItem = new SafeHtml('mainContent', $domCrawler, "//article/div[@class='box']");
    $storeTitles = new TextArray('tags', $domCrawler, "//div[@class='box']/div[@class='cp-admin-row']//a[@rel='tag']");
    $storeTitles->setRequired(false);
    $bags[$index]->setItems($featuredImage, $titleItem, $htmlContentItem, $storeTitles);
    $bags[$index]->build();
}
ToolBox::debugResult($bags);
```

### More advanced example:
```php

    $pool = $httpClient->concurrentRequests($urls);
    $bags = [];
    foreach ($pool as $index => $response) {
        try {
            if ($response instanceof \GuzzleHttp\Exception\RequestException) {
                continue;
            }
            $domCrawler = new SymfonyDomCrawler($response->getBody()->getContents());
            $bags[$index] = new Bag($urls[$index]);

            $titleItem = new Text('title', $domCrawler, "//div[@class='grayTopCnt topInfo ']/div[@class='row'][2]/div[@class='col col12']/div[@class='title']/h1");
            $featuredImage = new Text('featuredImage', $domCrawler, "//div[@class='imgWrp']/div[@class='topImg mainVideo']/div[@class='item']/picture/img[@class='lazyload']/@data-src");
            $safeHtmlContent = new \Pkscraper\Items\SafeHtml('contentTest', new SymfonyDomCrawler($resp->getBody()->getContents()), "//div[@id='main-post']/div[@class='post']/div[@class='blog-standard']/div[@class='cntTxt']");
            $safeHtmlContent->addTransformer(new \Pkscraper\Transform\ImageRelativeSourceToAbsoluteTransformer($httpClient->getCurrentUrlWithoutPath()));
            $safeHtmlContent->addRemover(new \Pkscraper\Remove\ElementByTagByIndexRemover('img', 0));
            $safeHtmlContent->addRemover(new \Pkscraper\Remove\ElementByTagByIndexRemover('a', 0));
            $safeHtmlContent->addCleaner(new \Pkscraper\Clean\TextCleaner('<p>                Loading...                						</p>', ''));
            $safeHtmlContent->addRemover(new \Pkscraper\Remove\ElementsByTagRemover('footer'));
            $safeHtmlContent->addTransformer(new \Pkscraper\Transform\ImageRelativeSourceToAbsoluteTransformer($httpClient->getCurrentUrlWithoutPath()));
            $safeHtmlContent->addCleaner(new \Pkscraper\Clean\RegExCleaner('/<\\/?a(\\s+.*?>|>(?1))/', ''));
            $safeHtmlContent->addCleaner(new \Pkscraper\Clean\RegExCleaner('/<\\/?img(\\s+.*?>|>)(?1)/', ''));
            $safeHtmlContent->addRemover(new \Pkscraper\Remove\ElementByIdRemover('jp-post-flair'));
            $safeHtmlContent->addRemover(new \Pkscraper\Remove\ElementByClassByIndexRemover('size-full', 0));
            $safeHtmlContent->addCleaner(new \Pkscraper\Clean\TextCleaner('";                        i.innerHTML=l};                      //]]&gt;                    ', ''));
            $safeHtmlContent->addRemover(new \Pkscraper\Remove\ElementsContainsClassRemover('post-'));
            $safeHtmlContent->addTransformer(new ImageRelativeSourceToAbsoluteTransformer($httpClient->getCurrentUrlWithoutPath()));
            $domRunnerBeforePurify = function () {
                foreach ($this->getAttributesValue('img', 'data-src') as $index => $imgLink) {
                    $paths = \Pkscraper\ToolBox::getUrlPathComponents($imgLink);

                    if (isset($paths[3]) && $paths[3] === "YouTube") { //this let me find which img element is used for youtube and fix them
                        $youtubeId = substr($paths[4], 0, -4);

                        $iframe = $this->DOMDocument->createElement('iframe');
                        $iframe->setAttribute('src', "https://www.youtube.com/embed/$youtubeId");

                        $elementToBeReplaced = $this->getNodeList('img')->item($index);
                        if ($elementToBeReplaced) {
                            $this->replaceElement($elementToBeReplaced, $iframe);
                        }
                    }
                }
                foreach ($this->getAttributesValue('img', 'data-src') as $index => $imgLink) { //the rest is not a youtube but only image
                    $this->replaceImagesAttributes("", $imgLink);
                }

            };


            $htmlContent = new SafeHtml('mainContent', $domCrawler,
                "//div[@class='main withShare']/div[@class='content details']/div[@class='cntTxt']", [
                    'h1',
                    'h2',
                    'h3',
                    'h4',
                    'h5',
                    'h6',
                    'div',
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
                    'span',
                    'table',
                    'thead',
                    'tbody',
                    'td',
                    'th',
                    'tr',
                    'sub',
                    'sup',
                ], $domRunnerBeforePurify);

            $bags[$index]->setItems($featuredImage, $titleItem, $htmlContent);
            $bags[$index]->build();
        } catch (\Exception $e) {
            print 'ok';
        }
    }
    echo(json_encode(Collector::collect($bags), JSON_UNESCAPED_UNICODE));
```