<?php

declare(strict_types=1);

namespace Console\Record;

class RecordInfo
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $recordName;

    /**
     * @var string
     */
    private $ipVersion;

    public function __construct(string $ipVersion, string $domain, string $recordName)
    {
        $this->domain = $domain;
        $this->recordName = $recordName;
        $this->ipVersion = $ipVersion;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getRecordName(): string
    {
        return $this->recordName;
    }

    public function getType(): string
    {
        return RecordTypes::RECORD_TYPE_IPV_MAPPING[$this->ipVersion];
    }

    public function getIpVersion(): string
    {
        return $this->ipVersion;
    }
}
