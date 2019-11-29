<?php

declare(strict_types=1);

namespace Console\IP\Resolvers;

use GuzzleHttp\ClientInterface;

abstract class AbstractResolver implements VersionResolver
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function supportsVersion(string $version): bool
    {
        return $version === $this->supportedVersion();
    }

    public function resolve(): string
    {
        return $this->requestIP();
    }

    abstract protected function ipVersionRegex(): string;

    private function requestIP(): ?string
    {
        $response = $this->client->request('GET')->getBody()->getContents();

        $ip = $this->extractIP($response);
        if (null === $ip) {
            throw new \UnexpectedValueException('Ip could not be determined.');
        }

        return $ip;
    }

    private function extractIP(?string $responseString): ?string
    {
        preg_match($this->ipVersionRegex(), $responseString, $matches);

        $ip = reset($matches);

        return $ip ?: null;
    }
}
