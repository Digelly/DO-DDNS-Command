<?php

declare(strict_types=1);

namespace Console\DigitalOcean;

class DORecord
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $domain;

    public function __construct(string $id, string $ip, string $domain)
    {
        $this->id = $id;
        $this->ip = $ip;
        $this->domain = $domain;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
