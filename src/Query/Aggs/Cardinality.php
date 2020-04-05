<?php

namespace AlBundy\ZfElasticSearch\Query\Aggs;

class Cardinality
{
    /**
     * const MAX_PRECISION_TRESHOLD
     */
    const MAX_PRECISION_TRESHOLD = 400000;

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
     * @return Cardinality
     */
    public function setField(string $field): Cardinality
    {
        $this->body['field'] = $field;
        return $this;
    }

    /**
     * @param int $precision
     * @return Cardinality
     */
    public function setPrecisionThreshold(int $precision): Cardinality
    {
        $this->body['precision_threshold'] = $precision;
        return $this;
    }
}
