<?php

namespace AlBundy\ZfElasticSearch\Repository;

use Elasticsearch\Client;
use AlBundy\ZfElasticSearch\Document\AbstractDocument;
use AlBundy\ZfElasticSearch\Type\AbstractType;

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
     * @param $document
     * @return array
     */
    public function save(AbstractDocument $document)
    {
        $body = $document->toArray();

        $params = [
            'index' => $this->getIndex(),
            'type' => $this->getType()->getName(),
            'refresh' => true,
            'body' => $body,
            'id' => $body['id'] ?? null
        ];

        return $this->getClient()->index($params);
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

            $this->getClient()->bulk($params);

            if ($documents->count() < $limit) {
                break;
            }

            $offset += $limit;
        } while (true);

        return $offset;
    }
}
