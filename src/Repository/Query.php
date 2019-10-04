<?php

namespace ZfElasticSearch\Repository;

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
        $result = $this->repository->getClient()->search([
            'index' => $this->repository->getIndex(),
            'type' => $this->repository->getType()->getName(),
            'body' => $this->body,
        ]);

        return $result;
    }
}
