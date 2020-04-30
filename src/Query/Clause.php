<?php

namespace AlBundy\ZfElasticSearch\Query;

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
    public function multiMatch(): SubClause
    {
        return $this->subClause('multi_match');
    }

    /**
     *
     */
    public function match(): SubClause
    {
        return $this->subClause('match');
    }

    /**
     *
     */
    public function matchPhrase(): SubClause
    {
        return $this->subClause('match_phrase');
    }

    /**
     *
     */
    public function term(): SubClause
    {
        return $this->subClause('term');
    }

    /**
     *
     */
    public function terms(): SubClause
    {
        return $this->subClause('terms');
    }

    /**
     *
     */
    public function range(): SubClause
    {
        return $this->subClause('range');
    }

    /**
     *
     */
    public function prefix(): SubClause
    {
        return $this->subClause('prefix');
    }

    /**
     * @param $clause
     * @param null $key
     */
    public function add($clause, $key = null): void
    {
        if (is_null($key)) {
            $this->body[] = $clause;
        } else {
            $this->body[$key] = $clause;
        }
    }

    public function bool(): \AlBundy\ZfElasticSearch\Query\Boolean
    {
        if (empty($this->bool)) {
            if (empty($this->body)) {
                $this->body = [];
            }
            $bool = ['bool' => []];
            $this->body[] = &$bool;
            $this->bool = new Boolean($bool['bool']);
        }

        return $this->bool;
    }

    public function aggs(string $name): Aggs
    {
        if (empty($this->aggs)) {
            if (empty($this->aggs[$name])) {
                $this->body['aggs'][$name] = [];
            }
            $this->aggs[$name] = new Aggs($this->body['aggs'][$name]);
        }

        return $this->aggs[$name];
    }

    /**
     * @return Nested
     */
    public function nested(string $path): Nested
    {
        if (empty($this->nested[$path])) {
            $nested = [
                'nested' => [
                    'path' => $path
                ]
            ];
            $this->body[] = &$nested;
            $this->nested[$path] = new Nested($nested['nested']);
        }

        return $this->nested[$path];
    }

    private function subClause(string $key): SubClause
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
