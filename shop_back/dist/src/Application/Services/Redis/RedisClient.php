<?php

namespace App\Application\Services\Redis;

use Predis;

class RedisClient
{
    public Predis\Client $client;

    public function __construct()
    {
        $this->client = new Predis\Client('tcp://127.0.0.1:46380');
    }
}
