<?php

namespace AlBundy\ZfElasticSearch\Query\Aggs;

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

    /**
     * @param string $field
     * @return Terms
     */
    public function setField(string $field): Terms
    {
        $this->body['field'] = $field;
        return $this;
    }

    /**
     * @param int $size
     * @return Terms
     */
    public function setSize(int $size): Terms
    {
        $this->body['size'] = $size;
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return Terms
     */
    public function setOrder(string $key, $value): Terms
    {
        $this->body['order'][$key] = $value;
        return $this;
    }
}
