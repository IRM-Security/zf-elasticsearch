<?php

namespace AlBundy\ZfElasticSearch\Client;

use Elasticsearch\Serializers\SerializerInterface;

class JsonSerializer implements SerializerInterface
{
    /**
     * @param $data
     * @return string
     */
    public function serialize($data): string
    {
        return is_string($data) ? $data : json_encode($data, JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * @param string $data
     * @param null $headers
     * @return array
     */
    public function deserialize($data, $headers = null): array
    {
        return json_decode($data, true);
    }
}
