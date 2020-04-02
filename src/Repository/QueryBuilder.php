<?php

namespace AlBundy\ZfElasticSearch\Repository;

use AlBundy\ZfElasticSearch\Query\Boolean;
use AlBundy\ZfElasticSearch\Query\Aggs;
use AlBundy\ZfElasticSearch\Query\Nested;

class QueryBuilder
{
    /**
     * @var array
     */
    private $body = [];

    /**
     * @var
     */
    private $bool;

    /**
     * @var
     */
    private $aggs;

    /**
     * @var
     */
    private $nested;

    /**
     * @var DefaultRepository
     */
    private $repository;

    /**
     * QueryBuilder constructor.
     * @param DefaultRepository $repository
     */
    public function __construct(DefaultRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     */
    public function bool()
    {
        if (!$this->bool) {
            if (empty($this->body['query']['bool'])) {
                $this->body['query']['bool'] = [];
            }
            $this->bool = new Boolean($this->body['query']['bool']);
        }

        return $this->bool;
    }

    /**
     * @return Aggs
     */
    public function aggs(string $name)
    {
        if (empty($this->aggs[$name])) {
            if (empty($this->body['aggs'][$name])) {
                $this->body['aggs'][$name] = [];
            }

            $this->aggs[$name] = new Aggs($this->body['aggs'][$name]);
        }

        return $this->aggs[$name];
    }

    /**
     * @return Nested
     */
    public function nested(string $path)
    {
        if (empty($this->nested[$path])) {
            if (empty($this->body['query']['nested'][$path])) {
                $this->body['query']['nested'][$path] = [];
            }
            $this->nested = new Nested($this->body['query']['nested'][$path]);
        }

        return $this->nested;
    }

    /**
     * @param string $sort
     * @param string|null $order
     * @return QueryBuilder
     */
    public function addOrderBy(string $sort, string $order = null): self
    {
        $this->body['sort'][] = [$sort => $order ?? 'asc'];

        return $this;
    }

    /**
     * @param $fields
     * @return $this
     */
    public function addHighlights($fields)
    {
        if (!is_array($fields)) {
            $fields = [$fields];
        }

        foreach ($fields as $field) {
            $this->body['highlight']['fields'][$field] = (object)null;
        }

        return $this;
    }

    /**
     * @param int $firstResult
     * @return QueryBuilder
     */
    public function setFirstResult(int $firstResult): self
    {
        $this->body['from'] = $firstResult;

        return $this;
    }

    /**
     * @param int $maxResults
     * @return QueryBuilder
     */
    public function setMaxResults(int $maxResults): self
    {
        $this->body['size'] = $maxResults;

        return $this;
    }

    /**
     * @return Query
     */
    public function getQuery(): Query
    {
        if (empty($this->body['query'])) {
            $this->body['query']['match_all'] = new \stdClass();
        }

        return new Query($this->repository, $this->body);
    }
}
