<?php namespace Pkscraper\Dom;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use InvalidArgumentException;
use Pkscraper\Exceptions\EmptyDocument;
use Pkscraper\ToolBox;

class Dom
{
    /**
     * @var DOMDocument
     */
    public $DOMDocument;

    /**
     * Dom constructor.
     *
     * @param string $document
     */
    public function __construct(string $document)
    {
        $this->DOMDocument = new DOMDocument();
        $this->handleInvalidHtml();
        $this->loadDocument($document);
    }

    public function get()
    {
        return utf8_decode($this->DOMDocument->saveHTML($this->DOMDocument->documentElement));
    }

    /**
     * @param string $element Html elements: 'img','p','h1'
     * @return DOMNodeList
     */
    public function getNodeList(string $element): DOMNodeList
    {
        return $this->DOMDocument->getElementsByTagName($element);
    }

    /**
     * @param string $element
     * @param string $attribute Html attribute 'src','href','alt'
     * @return array
     */
    public function getAttributesValue(string $element, string $attribute): array
    {
        $nodeList = $this->getNodeList($element);
        $attributeValues = [];
        foreach ($nodeList as $element) {
            $attributeValues[] = $element->getAttribute($attribute);
        }

        return $attributeValues;
    }

    /**
     * @param string $element
     * @param array $attributes This is the attributes values that it's going to be replaced
     * @throws InvalidArgumentException
     */
    public function replaceAttributes(string $element, array $attributes)
    {
        $nodeList = $this->getNodeList($element);
        if (!$this->isItemsEquals($nodeList, $attributes)) {
            throw new InvalidArgumentException('Items are not equal. The number of original elements are: ' . $nodeList->length . ' but you give ' . count($attributes));
        }
        foreach ($nodeList as $index => $element) {
            foreach ($attributes[$index] as $attribute => $attributeValue) {
                $element->setAttribute($attribute, utf8_encode($attributeValue));
            }
        }
    }

    public function removeElementsAndItsContent(array $elements)
    {
        foreach ($elements as $element) {
            $nodeList = $this->getNodeList($element);
            while ($nodeList->length) {
                $node = $nodeList->item(0);
                $node->parentNode->removeChild($node);
            }
        }
    }

    public function removeElementByDomElement(DOMElement $element)
    {
        $element->parentNode->removeChild($element);
    }

    public function removeElementById(string $id)
    {
        $elementToBeDeleted = $this->DOMDocument->getElementById($id);
        if ($elementToBeDeleted) {
            $elementToBeDeleted->parentNode->removeChild($elementToBeDeleted);
        }
    }

    public function removeElementsByClass(string $class)
    {
        $xpath = new \DOMXPath($this->DOMDocument);
        foreach ($xpath->query("//div[contains(attribute::class, $class)]") as $e) {
            $e->parentNode->removeChild($e);
        }
    }

    public function setAttributes(string $element, array $attributes)
    {
        $nodeList = $this->getNodeList($element);
        foreach ($nodeList as $index => $element) {
            foreach ($attributes as $attribute => $value) {
                $element->setAttribute($attribute, utf8_encode($value));
            }
        }
    }

    /**
     * @param $attribute 'style', 'href', 'src'
     */
    public function removeAttribute(string $attribute)
    {
        $xpath = new \DOMXPath($this->DOMDocument);
        $nodes = $xpath->query("//*[@$attribute]");
        foreach ($nodes as $node) {
            $node->removeAttribute($attribute);
        }
    }

    public function removeAllAttributes()
    {
        $xpath = new \DOMXPath($this->DOMDocument);
        $nodes = $xpath->query('//@*');
        foreach ($nodes as $node) {
            $node->parentNode->removeAttribute($node->nodeName);
        }
    }

    public function removeAllAttributesExcept(array $attributes)
    {
        $xpath = new \DOMXPath($this->DOMDocument);
        $nodes = $xpath->query('//@*');
        foreach ($nodes as $node) {
            if (!in_array($node->nodeName, $attributes)) {
                $node->parentNode->removeAttribute($node->nodeName);
            }
        }
    }

    public function replaceElement(DOMElement $elementToBeReplaced, DOMElement $newElement)
    {
        $elementToBeReplaced->parentNode->replaceChild($newElement, $elementToBeReplaced);
    }

    public function isItemsEquals(DOMNodeList $elements, array $attributesValue): bool
    {
        return $elements->length === count($attributesValue);
    }

    public function wrapDocument($wrappedElement = 'div', $wrappedClass = 'wrapper')
    {
        $this->DOMDocument->documentElement->nodeValue = '<' . $wrappedElement . ' class="' . $wrappedClass . '"' . '>' . $this->DOMDocument->documentElement->nodeValue . '</' . $wrappedElement . '>';
    }

    public function getTextContent()
    {
        return $this->DOMDocument->textContent;
    }

    /**
     * @param string $document
     * @throws EmptyDocument
     */
    protected function loadDocument(string $document)
    {
        if (empty($document)) {
            throw new EmptyDocument();
        }
        $this->DOMDocument->loadHTML($document, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    }

    /**
     * prevent errors of not valid html on load document
     */
    protected function handleInvalidHtml()
    {
        if (libxml_use_internal_errors(true) === true) {
            libxml_clear_errors(); //***IMPORTANT FOR PERFORMANCE*** Clear libxml error buffer to prevent Memory Leak
        }
    }


    public function replaceImagesAttributes(string $name, string $storagePath, $class = 'img-responsive article-image')
    {
        $numberOfImages = count($this->getAttributesValue('img', 'src'));
        $attributes = [];
        for ($i = 0; $i < $numberOfImages; $i++) {
            $attributes[$i]['src'] = $storagePath . $name;
            $attributes[$i]['alt'] = $name;
            $attributes[$i]['class'] = $class;
        }
        $this->replaceAttributes('img', $attributes);
    }

    public function replaceImagesSourceToAbsolute(string $srcPrefix)
    {
        $nodeList = $this->getNodeList('img');
        foreach ($nodeList as $index => $element) {
            $currentAttrSrc = $element->getAttribute('src');
            if (ToolBox::isRelativeUrl($currentAttrSrc)) {
                $element->setAttribute('src', $srcPrefix . $currentAttrSrc);
            }
        }
    }

}