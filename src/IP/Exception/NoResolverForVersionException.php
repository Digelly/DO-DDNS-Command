<?php

declare(strict_types=1);

namespace Console\IP\Exception;

final class NoResolverForVersionException extends \Exception
{
    public function __construct(string $ipVersion)
    {
        parent::__construct(sprintf('There is no resolver registered for ip version: %s', $ipVersion));
    }
}
