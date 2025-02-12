<?php

namespace AlBundy\ZfElasticSearch\Repository;

class Query
{
    /**
     * @var array
     */
    private $body;

    /**
     * @var DefaultRepository
     */
    private $repository;

    private $params = [];

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * QueryBuilder constructor.
     * @param DefaultRepository $repository
     * @param $body
     */
    public function __construct(DefaultRepository $repository, array $body)
    {
        $this->repository = $repository;
        $this->body = $body;
    }

    /**
     * @param callable $loader
     * @return mixed
     */
    public function getRawResult(callable $loader)
    {
        $result = $this->search();

        $documents = [];

        foreach ($result['hits']['hits'] as $hit) {
            $documents[] = $loader($hit);
        }

        return $documents;
    }

    /**
     * @param callable $loader
     * @return mixed
     */
    public function getResult(callable $loader)
    {
        $result = $this->search();

        $documents = [];

        foreach ($result['hits']['hits'] as $hit) {
            $hit['found'] = true;
            $documents[$hit['_id']] = $this->repository->getType()->getDocument()->fromResult($hit);
        }

        return $loader($documents, $result['hits']['total'], $result);
    }

    /**
     * @param callable $loader
     * @return mixed
     */
    public function getIds(callable $loader)
    {
        $result = $this->search();

        $documents = [];
        foreach ($result['hits']['hits'] as $hit) {
            $documents[$hit['_id']] = $hit['_id'];
        }

        return $loader($documents, $result['hits']['total'], $result);
    }

    /**
     * @return array
     */
    protected function search()
    {
        $result = $this->repository->getClient()->search(array_merge([
            'index' => $this->repository->getIndex(),
            'type' => $this->repository->getType()->getName(),
            'body' => $this->body
        ], $this->params));

        return $result;
    }

    public function getBody()
    {
        return json_encode($this->body, JSON_PRETTY_PRINT);
    }
}
