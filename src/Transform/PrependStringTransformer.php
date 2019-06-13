<?php


namespace Pkscraper\Transform;


class PrependStringTransformer extends Transformer
{

    protected $prepend;

    public function __construct(string $prepend)
    {
        $this->prepend = $prepend;
    }

    public function transform()
    {
        return $this->prepend . $this->document;
    }
}