<?php

namespace AlBundy\ZfElasticSearch\Query\Terms;

class Terms
{
    /**
     * @var array
     */
    private $body = [];

    /**
     * Aggs constructor.
     * @param array $body
     */
    public function __construct(array &$body)
    {
        $this->body = &$body;
    }

    public function setField(string $field): Terms
    {
        $this->body['field'] = $field;
        return $this;
    }


    public function setSize(int $size): Terms
    {
        $this->body['size'] = $size;
        return $this;
    }
}
