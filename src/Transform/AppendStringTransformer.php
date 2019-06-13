<?php


namespace Pkscraper\Transform;


class AppendStringTransformer extends Transformer
{

    protected $append;

    public function __construct(string $append)
    {
        $this->append = $append;
    }

    public function transform()
    {
        return $this->document . $this->append;
    }
}