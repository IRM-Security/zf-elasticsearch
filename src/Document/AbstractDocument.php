<?php

namespace AlBundy\ZfElasticSearch\Document;

/**
 * Class AbstractDocument
 */
abstract class AbstractDocument
{
    /**
     * @param $model
     * @return AbstractDocument
     */
    abstract public static function fromModel($model);

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $highlight = [];

    /**
     * @param null $field
     * @return array
     */
    public function getHighlight($field = null)
    {
        return $field ? ($this->highlight[$field] ?? []) : $this->highlight;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return AbstractDocument
     */
    public static function fromResult($data)
    {
        if (!$data || !$data['found']) {
            return null;
        }

        $document = new static();

        $document->data = $data['_source'];

        $document->highlight = $data['highlight'] ?? [];

        return $document;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }
}
