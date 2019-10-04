<?php

namespace ZfElasticSearch\Query;

class Clause
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
     *
     */
    public function multiMatch()
    {
        return $this->subClause('multi_match');
    }

    /**
     *
     */
    public function match()
    {
        return $this->subClause('match');
    }

    /**
     *
     */
    public function matchPhrase()
    {
        return $this->subClause('match_phrase');
    }

    /**
     *
     */
    public function term()
    {
        return $this->subClause('term');
    }

    /**
     *
     */
    public function terms()
    {
        return $this->subClause('terms');
    }

    /**
     *
     */
    public function range()
    {
        return $this->subClause('range');
    }

    /**
     * @param $key
     * @return SubClause
     */
    private function subClause($key)
    {
        if (empty($this->{$key})) {
            if (empty($this->body)) {
                $this->body = [];
            }
            $this->{$key} = new SubClause($key, $this->body);
        }

        return $this->{$key};
    }
}
