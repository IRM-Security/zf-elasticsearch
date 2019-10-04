<?php

namespace ZfElasticSearch;

//use ZfElasticSearch\Document\AbstractDocument;
use ZfElasticSearch\Factory\RepositoryManagerFactory;
use ZfElasticSearch\Repository\RepositoryManager;
//use ZfElasticSearch\Type\AbstractType;

class Module
{
    public function getConfig()
    {
        return [
            'zf-elasticsearch' => [
                'dsn' => [
                    'host' => '127.0.0.1',
                    'port' => 9200,
                    'index' => 'test',
                ],
//                AbstractType::class => [
//                    'document' => AbstractDocument::class,
//                    'properties' => [
//                        'id' => [
//                            'type' => 'integer',
//                        ],
//                        'sectorId' => [
//                            'type' => 'integer',
//                        ],
//                        'countryId' => [
//                            'type' => 'integer',
//                        ],
//                        'title' => [
//                            'type' => 'text',
//                            'analyzer' => 'english'
//                        ],
//                        'text' => [
//                            'type' => 'text',
//                            'analyzer' => 'english'
//                        ],
//                        'publicationTypeId' => [
//                            'type' => 'integer',
//                        ],
//                        'isPublished' => [
//                            'type' => 'boolean',
//                        ],
//                        'publishedDate' => [
//                            'type' => 'date',
//                        ],
//                        'index' => 'test',
//                    ]
//                ]
            ],
            'service_manager' => [
                'factories' => [
                    RepositoryManager::class => RepositoryManagerFactory::class,
                ]
            ]
        ];
    }
}
