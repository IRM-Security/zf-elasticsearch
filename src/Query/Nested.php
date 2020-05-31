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
