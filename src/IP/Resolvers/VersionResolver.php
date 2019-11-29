<?php

declare(strict_types=1);

namespace Console\IP\Resolvers;

interface VersionResolver
{
    public function supportedVersion(): string;

    public function supportsVersion(string $version): bool;

    public function resolve(): string;
}
