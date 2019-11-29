<?php

declare(strict_types=1);

namespace Console\IP\Resolvers;

use Console\IP\Exception\InvalidIPException;
use Console\IP\IPVersions;
use GuzzleHttp\ClientInterface;

class V4Resolver implements VersionResolver
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function supportedVersion(): string
    {
        return IPVersions::IP_V4;
    }

    public function supportsVersion(string $version): bool
    {
        return $version === $this->supportedVersion();
    }

    public function resolve(): string
    {
        $ip = trim($this->requestIP());

        if (!$this->isValidIp($ip)) {
            throw new InvalidIPException($ip, $this->supportedVersion());
        }

        return $ip;
    }

    private function isValidIp(?string $ip): bool
    {
        $regex = '/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/';

        return (bool) preg_match($regex, $ip, $matches);
    }

    private function requestIP(): ?string
    {
        return $this->client->request('GET', '', [

        ])->getBody()->getContents();
    }
}
