<?php

namespace AlBundy\ZfElasticSearch\Query;

use AlBundy\ZfElasticSearch\Query\Aggs\Cardinality;
use AlBundy\ZfElasticSearch\Query\Aggs\Terms;

class Aggs
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
     * @var Clause
     */
    private $terms;

    /**
     * @return Terms
     */
    public function terms(): Terms
    {
        if (!$this->terms) {
            if (empty($this->body['terms'])) {
                $this->body['terms'] = [];
            }
            $this->terms = new Terms($this->body['terms']);
        }

        return $this->terms;
    }

    /**
     * @var Clause
     */
    private $cardinality;

    /**
     * @return Cardinality
     */
    public function cardinality(): Cardinality
    {
        if (!$this->cardinality) {
            if (empty($this->body['cardinality'])) {
                $this->body['cardinality'] = [];
            }
            $this->cardinality = new Cardinality($this->body['cardinality']);
        }

        return $this->cardinality;
    }
    /**
     *
     * Nested aggs
     *
     * @param string $name
     * @return Aggs
     */
    public function aggs(string $name)
    {
        $clause = new Clause($this->body);
        return $clause->aggs($name);
    }
}
