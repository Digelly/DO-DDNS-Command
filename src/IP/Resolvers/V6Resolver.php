<?php

declare(strict_types=1);

namespace Console\IP\Resolvers;

use Console\IP\Exception\InvalidIPException;
use Console\IP\IPVersions;
use GuzzleHttp\ClientInterface;

class V6Resolver implements VersionResolver
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
        return IPVersions::IP_V6;
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
        $regex = '/(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))/';

        return (bool) preg_match($regex, $ip, $matches);
    }

    private function requestIP(): ?string
    {
        return $this->client->request('GET', '', [

        ])->getBody()->getContents();
    }
}
