<?php

declare(strict_types=1);

namespace Console\IP;

use Console\DigitalOcean\DOClient;
use Console\Record\RecordInfo;

class IPUpdater
{
    /**
     * @var DOClient
     */
    private $DOClient;

    /**
     * @var IPResolver
     */
    private $ipResolver;

    public function __construct(IPResolver $ipResolver, DOClient $DOClient)
    {
        $this->ipResolver = $ipResolver;
        $this->DOClient = $DOClient;
    }

    public function updateRecord(RecordInfo $recordInfo): void
    {
        $ip = $this->ipResolver->resolve($recordInfo);
        $record = $this->DOClient->getDomainRecord($recordInfo);

        if ($this->areIPSEqual($record->getIp(), $ip)) {
            return;
        }

        $this->DOClient->updateRecord($record, $ip);
    }

    private function areIPSEqual(string $current, string $actual): bool
    {
        return $current === $actual;
    }
}
