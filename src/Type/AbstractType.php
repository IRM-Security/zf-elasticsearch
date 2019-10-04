<?php

namespace ZfElasticSearch\Type;

use ZfElasticSearch\Document\AbstractDocument;

/**
 * Class AbstractType
 * @package Elastic\Model
 */
abstract class AbstractType
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var AbstractDocument
     */
    protected $document = null;

    /**
     * AbstractType constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return get_class($this);
    }

    /**
     * @return AbstractDocument
     */
    public function getDocument(): AbstractDocument
    {
        if (!$this->document) {
            $this->document = new $this->config['document'];
        }

        return $this->document;
    }
}
