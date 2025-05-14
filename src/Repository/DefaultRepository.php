<?php

namespace AlBundy\ZfElasticSearch\Repository;

use AlBundy\ZfElasticSearch\Document\AbstractDocument;
use AlBundy\ZfElasticSearch\Type\AbstractType;
use Elasticsearch\Client;

/**
 * ArticleType Object
 */
class DefaultRepository
{
    /**
     * @var AbstractType
     */
    protected $type;

    /**
     * @var Client
     */
    private $client;

    private $params = [];

    /**
     * ArticleRepository constructor.
     * @param Client $client
     * @param AbstractType $type
     */
    public function __construct(
        Client $client,
        AbstractType $type
    ) {
        $this->type = $type;
        $this->client = $client;

        // FIXME exceptions, interfaces ...
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return AbstractType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getType()->getName();
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->getType()->getIndex();
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->getType()->getProperties();
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->getType()->getSettings();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return new QueryBuilder($this);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function find(string $id)
    {
        $data = $this->getClient()->get([
            'index' => $this->getIndex(),
            'type' => $this->getType()->getName(),
            'id' => $id,
        ]);

        return $this->getType()->getDocument()->fromResult($data);
    }

    /**
     * @param AbstractDocument $document
     * @param array $options
     * @return array
     */
    public function save(AbstractDocument $document, array $options = [])
    {
        $body = $document->toArray();

        $params = [
            'index' => $this->getIndex(),
            'type' => $this->getType()->getName(),
            'body' => $body,
            'id' => $body['id'] ?? null
        ];

        $defaults = [
            'refresh' => true
        ];

        $this->addOptions($params, array_merge($defaults, $options));

        return $this->getClient()->index($params);
    }

    /**
     * @param AbstractDocument $document
     * @param array $options
     * @return array
     */
    public function update(AbstractDocument $document, array $options = [])
    {
        $body = $document->toArray();

        $params = [
            'index' => $this->getIndex(),
            'type' => $this->getType()->getName(),
            'body' => ['doc' => $body],
            'id' => $body['id'] ?? null
        ];

        $defaults = [
            'refresh' => false,
            'retry_on_conflict' => 1,
        ];

        $this->addOptions($params, array_merge($defaults, $options));

        return $this->getClient()->update($params);
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        $params = [
            'index' => $this->getIndex(),
            'type' => $this->getType()->getName(),
            'id' => $id
        ];

        return $this->getClient()->delete($params);
    }

    /**
     * @param callable $documentsLoader
     * @return int
     */
    public function bulkIndex(callable $documentsLoader)
    {
        $offset = 0;
        $limit = 500;
        $document = $this->getType()->getDocument();

        do {
            /** @var \Countable|\Iterator $documents */
            $documents = $documentsLoader($offset, $limit);

            if (!$documents->count()) {
                break;
            }

            $params = ['body' => []];

            foreach ($documents as $model) {
                $params['body'][] = [
                    'index' => [
                        '_index' => $this->getIndex(),
                        '_type' => $this->getType()->getName(),
                        '_id' => $model->getId()
                    ]
                ];

                $params ['body'][] = $document->fromModel($model)->toArray();
            }

            $this->getClient()->bulk(array_merge($this->params, $params));

            if ($documents->count() < $limit) {
                break;
            }

            $offset += $limit;
        } while (true);

        return $offset;
    }

    private function addOptions(array &$params, array $options)
    {
        foreach ($options as $k => $v) {
            if (isset($params[$k])) {
                continue;
            }
            $params[$k] = $v;
        }
    }
}
