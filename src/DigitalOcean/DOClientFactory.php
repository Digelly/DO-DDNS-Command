<?php

declare(strict_types=1);

namespace Console\DigitalOcean;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class DOClientFactory
{
    public function create(): DOClient
    {
        return new DOClient($this->createNewClient());
    }

    private function createNewClient(): ClientInterface
    {
        $token = getenv('DO_API_TOKEN');
        if (!$token) {
            throw new \UnexpectedValueException('You must configure the DO_API_TOKEN env var');
        }

        return new Client([
            'base_uri' => 'https://api.digitalocean.com/v2/',
            'timeout'  => 2.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Bearer %s', $token),
            ]
        ]);
    }
}
