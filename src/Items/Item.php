<?php

namespace Pkscraper\Items;

use Pkscraper\Clean\Cleaner;
use Pkscraper\Cloning\Cloner;
use Pkscraper\Dom\DomCrawler;
use Pkscraper\Exceptions\EmptyItemException;
use Pkscraper\Fix\Fixer;
use Pkscraper\Remove\Remover;
use Pkscraper\Transform\Transformer;

abstract class Item
{

    protected $domCrawler;

    protected $selector;

    protected $code;

    protected $extractedValue;

    protected $originalExtractedValue;

    protected $required = true;

    /**
     * @var Cleaner[]
     */
    protected $cleaners = [];

    /**
     * @var Transformer[]
     */
    protected $transformers = [];

    /**
     * @var Cloner[]
     */
    protected $cloners = [];

    /**
     * @var Fixer[]
     */
    protected $fixers = [];

    /**
     * @var Remover[]
     */
    protected $removers = [];

    /**
     * @return Cloner[]
     */
    public function getCloners(): array
    {
        return $this->cloners;
    }

    protected $clonedValue;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @inheritDoc
     */
    public function __construct(string $code, DomCrawler $domCrawler, string $selector)
    {
        $this->code = $code;
        $this->domCrawler = $domCrawler;
        $this->selector = $selector;
    }

    /**
     * @return DomCrawler
     */
    public function getDomCrawler(): DomCrawler
    {
        return $this->domCrawler;
    }

    /**
     * @param DomCrawler $domCrawler
     */
    public function setDomCrawler(DomCrawler $domCrawler)
    {
        $this->domCrawler = $domCrawler;
    }

    /**
     * @return mixed
     */
    public function getExtractedValue()
    {
        return $this->extractedValue;
    }

    public function getClonedValue()
    {
        return $this->clonedValue;
    }

    public function addCleaner(Cleaner $cleaner)
    {
        $this->cleaners[] = $cleaner;
    }

    public function addTransformer(Transformer $transformer)
    {
        $this->transformers[] = $transformer;
    }

    public function addCloner(Cloner $cloner)
    {
        $this->cloners[] = $cloner;
    }

    public function addFixer(Fixer $fixer)
    {
        $this->fixers[] = $fixer;
    }

    public function addRemover(Remover $remover)
    {
        $this->removers[] = $remover;
    }

    protected function doClean()
    {
        foreach ($this->cleaners as $cleaner) {
            $cleaner->setDocument($this->extractedValue);
            $this->extractedValue = $cleaner->clean();
        }
    }

    protected function doTransform()
    {
        foreach ($this->transformers as $transformer) {
            $transformer->setDocument($this->extractedValue);
            $this->extractedValue = $transformer->transform();
        }
    }

    protected function doClone()
    {
        foreach ($this->cloners as $cloner) {
            $cloner->setDocument($this->originalExtractedValue);
            $this->clonedValue = $cloner->doClone();
        }
    }

    protected function doFix()
    {
        foreach ($this->fixers as $fixer) {
            $fixer->setDocument($this->extractedValue);
            $this->extractedValue = $fixer->fix();
        }
    }

    protected function doRemove()
    {
        foreach ($this->removers as $remover) {
            $remover->setDocument($this->extractedValue);
            $this->extractedValue = $remover->remove();
        }
    }

    /**
     * @throws EmptyItemException
     */
    public function build()
    {
        $this->doExtract();
        if (empty($this->extractedValue) && $this->isRequired()) {
            throw new EmptyItemException("code:" . $this->code . " selector:" . $this->selector);
        }
        $this->doClone();
        $this->doRemove();
        $this->doFix();
        $this->doClean();
        $this->doTransform();
    }

    abstract protected function doExtract();

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }
}