<?php

namespace ZfElasticSearch\Query;

class Boolean
{
    /**
     * @var array
     */
    private $body = [];

    /**
     * Boolean constructor.
     * @param array $body
     */
    public function __construct(array &$body)
    {
        $this->body = &$body;
    }

    /**
     * @var Clause
     */
    private $must;

    /**
     *
     */
    public function must()
    {
        if (!$this->must) {
            if (empty($this->body['must'])) {
                $this->body['must'] = [];
            }
            $this->must = new Clause($this->body['must']);
        }

        return $this->must;
    }

    /**
     * @var Clause
     */
    private $mustNot;

    /**
     *
     */
    public function mustNot()
    {
        if (!$this->mustNot) {
            if (empty($this->body['must_not'])) {
                $this->body['must_not'] = [];
            }
            $this->mustNot = new Clause($this->body['must_not']);
        }

        return $this->mustNot;
    }

    /**
     * @var Clause
     */
    private $filter;

    /**
     *
     */
    public function filter()
    {
        if (!$this->filter) {
            if (empty($this->body['filter'])) {
                $this->body['filter'] = [];
            }
            $this->filter = new Clause($this->body['filter']);
        }

        return $this->filter;
    }

    /**
     * @var Clause
     */
    private $should;

    /**
     *
     */
    public function should()
    {
        if (!$this->should) {
            if (empty($this->body['should'])) {
                $this->body['should'] = [];
            }
            $this->should = new Clause($this->body['should']);
        }

        return $this->should;
    }
}
