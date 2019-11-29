<?php

declare(strict_types=1);

namespace Console\IP\Exception;

final class InvalidIPException extends \Exception
{
    public function __construct(string $ip, string $version)
    {
        parent::__construct(sprintf('The resolved ip: `%s` is not a valid ip for ip version %s', $ip, $version));
    }
}
