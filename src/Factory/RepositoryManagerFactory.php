<?php

namespace ZfElasticSearch\Factory;

use ZfElasticSearch\Client\JsonSerializer;
use Elasticsearch\ClientBuilder;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AbstractRepositoryService
 * @package Elastic\Repository
 */
class RepositoryManagerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['zf-elasticsearch'];

        $client = ClientBuilder::create()
            ->setSerializer(new JsonSerializer())
            ->setHosts([$config['dsn']['host'] . ':' . $config['dsn']['port']])
            ->build();

        return new $requestedName($client, $config);
    }
}
