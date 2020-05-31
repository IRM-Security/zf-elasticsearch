<?php

namespace AlBundy\ZfElasticSearch\Repository;

use AlBundy\ZfElasticSearch\Query\Boolean;

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
     * @param string $sort
     * @param string|null $order
     * @return QueryBuilder
     */
    public function setOrderBy(string $sort, string $order = null): self
    {
        $this->body['sort'] = [];
        
        return $this->addOrderBy($sort, $order);
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
