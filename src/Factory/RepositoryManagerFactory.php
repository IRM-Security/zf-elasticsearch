<?php

namespace AlBundy\ZfElasticSearch\Factory;

use Elasticsearch\ClientBuilder;
use Interop\Container\ContainerInterface;
use AlBundy\ZfElasticSearch\Client\JsonSerializer;

/**
 * Class AbstractRepositoryService
 * @package Elastic\Repository
 */
class RepositoryManagerFactory
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['zf-elasticsearch'];

        $hosts = $config['hosts'] ?? [$config['dsn']['host'] . ':' . $config['dsn']['port']];

        $client = ClientBuilder::create()
            ->setSerializer(new JsonSerializer())
            ->setHosts($hosts)
            ->build();

        $documents = $config['documents'] ?? $config;

        return new $requestedName($client, $documents);
    }
}
