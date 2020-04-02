<?php

namespace AlBundy\ZfElasticSearch\Query;

use AlBundy\ZfElasticSearch\Query\Terms\Terms;

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
     *
     */
    public function terms()
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
