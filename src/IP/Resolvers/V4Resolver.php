<?php

declare(strict_types=1);

namespace Console\IP\Resolvers;

use Console\IP\IPVersions;

class V4Resolver extends AbstractResolver
{
    public function supportedVersion(): string
    {
        return IPVersions::IP_V4;
    }

    protected function ipVersionRegex(): string
    {
        return '/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/';
    }
}
