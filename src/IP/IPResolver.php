<?php

declare(strict_types=1);

namespace Console\IP;

use Console\IP\Exception\NoResolverForVersionException;
use Console\IP\Resolvers\VersionResolver;
use Console\Record\RecordInfo;

class IPResolver
{
    /**
     * @var VersionResolver[]
     */
    private $versionResolvers;

    public function __construct(array $versionResolvers)
    {
        foreach ($versionResolvers as $resolver) {
            $this->registerResolver($resolver);
        }
    }

    public function resolve(RecordInfo $recordInfo): string
    {
        $ipVersion = $recordInfo->getIpVersion();
        if (!array_key_exists($ipVersion, $this->versionResolvers)) {
            throw new NoResolverForVersionException($recordInfo->getIpVersion());
        }

        return $this->versionResolvers[$ipVersion]->resolve();
    }

    private function registerResolver(VersionResolver $resolver):void
    {
        $this->versionResolvers[$resolver->supportedVersion()] = $resolver;
    }
}
