<?php

namespace AlBundy\ZfElasticSearch\Query;

class SubClause
{
    /**
     * @var array
     */
    private $body = [];

    /**
     * @var string
     */
    private $key;

    /**
     * Boolean constructor.
     * @param string $key
     * @param array $body
     */
    public function __construct(string $key, array &$body)
    {
        $this->body = &$body;
        $this->key = $key;
    }

    /**
     * @param $value
     * @return SubClause
     */
    public function set(array $value)
    {
        $this->body = [];
        foreach ($value as $key => $val) {
            $this->add([$key => $val]);
        }
        return $this;
    }

    /**
     * @param $value
     * @return SubClause
     */
    public function add($value)
    {
        $this->body[] = [$this->key => $value];
        return $this;
    }
}
