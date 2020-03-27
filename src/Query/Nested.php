<?php

namespace AlBundy\ZfElasticSearch\Query;

class Nested
{
    /**
     * @var array
     */
    private $body = [];

    /**
     * @var array
     */
    private $bool = [];

    /**
     * Boolean constructor.
     * @param array $body
     */
    public function __construct(array &$body)
    {
        $this->body = &$body;
    }

    /**
     * @var Clause
     */
    private $path;

    /**
     *
     */
    public function path($value)
    {
        if (!$this->path) {
            if (empty($this->body['path'])) {
                $this->body['path'] = $value;
            }
            $this->path = $value;
        }

        return $this;
    }


    /**
     *
     */
    public function bool()
    {
        if (!$this->bool) {
            if (empty($this->body['query']['bool'])) {
                $this->body['query']['bool'] = [];
            }
            $this->bool = new Boolean($this->body['query']['bool']);
        }

        return $this->bool;
    }
}
