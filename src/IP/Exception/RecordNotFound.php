<?php

declare(strict_types=1);

namespace Console\IP\Exception;

final class RecordNotFound extends \Exception
{
    public function __construct(string $recordName)
    {
        parent::__construct(sprintf('There is no record with name: `%s`', $recordName));
    }
}
