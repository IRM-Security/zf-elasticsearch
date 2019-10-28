<?php

namespace AlBundy\ZfElasticSearch\Repository;

use Elasticsearch\Client;

/**
 * Class AbstractRepositoryService
 * @package Elastic\Repository
 */
class RepositoryManager
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $repositories = [];

    /**
     * AbstractRepositoryService constructor.
     * @param Client $client
     * @param array $config
     */
    public function __construct(Client $client, array $config)
    {
        $this->config = $config;

        $this->client = $client;

        // FIXME exceptions, interfaces ...
    }

    /**
     * @param string $type
     * @return DefaultRepository
     */
    public function getRepository(string $type): DefaultRepository
    {
        if (empty($this->repositories[$type])) {
            $serviceConfig = $this->config[$type];

            if (empty($serviceConfig['index']) && !empty($this->config['dsn']['index'])) {
                $serviceConfig['index'] = $this->config['dsn']['index'];
            }

            if (empty($serviceConfig['name'])) {
                $serviceConfig['name'] = $type;
            }

            $this->repositories[$type] = new DefaultRepository(
                $this->client,
                new $type($serviceConfig)
            );
        }

        return $this->repositories[$type];
    }

    /**
     * @param string $index
     * @return array
     */
    public function createIndex(string $index)
    {
        return $this->client->indices()->create([
            'index' => $index,
        ]);
    }

    /**
     * @param string $source
     * @param string $destination
     * @return array
     */
    public function reindex(string $source, string $destination): array
    {
        return $this->client->reindex([
            'refresh' => true,
            'body' => [
                'source' => [
                    'index' => $source,
                ],
                'dest' => [
                    'index' => $destination,
                ],
            ],
        ]);
    }

    /**
     * @param string $index
     * @return array
     */
    public function removeIndex(string $index): array
    {
        return $this->client->indices()->delete([
            'index' => $index,
        ]);
    }

    /**
     * @param DefaultRepository $repository
     * @return array
     */
    public function createRepository(DefaultRepository $repository)
    {
        $this->client->indices()->close([
            'index' => $repository->getIndex(),
        ]);

        ///

        $settings = [
            'index' => $repository->getIndex(),
            'body' => [
                'settings' => $repository->getSettings(),
            ],
        ];

        $settings = $this->client->indices()->putSettings($settings);

        ///

        $mapping = [
            'index' => $repository->getIndex(),
            'type' => $repository->getName(),
            'body' => [
                $repository->getName() => [
                    'properties' => $repository->getProperties(),
                ],
            ],
        ];

        $mapping = $this->client->indices()->putMapping($mapping);

        $this->client->indices()->open([
            'index' => $repository->getIndex(),
        ]);

        return [
            'settings' => $settings,
            'mapping' => $mapping
        ];
    }

    /**
     * @param DefaultRepository $repository
     * @return void
     */
    public function updateRepository(DefaultRepository $repository)
    {
        $index = $repository->getIndex();
        $this->createIndex('tmp' . $index);
        $this->reindex($index, 'tmp' . $index);
        $this->removeIndex($index);
        $this->createIndex($index);
        $this->createRepository($repository);
        $this->reindex('tmp' . $index, $index);
        $this->removeIndex('tmp' . $index);
    }
}
