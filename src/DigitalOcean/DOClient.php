<?php

declare(strict_types=1);

namespace Console\DigitalOcean;

use Console\IP\Exception\RecordNotFound;
use Console\Record\RecordInfo;
use GuzzleHttp\ClientInterface;

class DOClient
{
    /**
     * @var ClientInterface
     */
    private $DOApiClient;

    public function __construct(ClientInterface $DOApiClient)
    {
        $this->DOApiClient = $DOApiClient;
    }

    public function updateRecord(DORecord $record, string $ip): void
    {
        $uri = sprintf('domains/%s/records/%s', $record->getDomain(), $record->getId());

        $this->DOApiClient->request('PUT', $uri, ['json' => ['data' => $ip]]);
    }

    public function getDomainRecord(RecordInfo $recordInfo): DORecord
    {
        $records = $this->filterRecords($this->getDomainRecords($recordInfo->getDomain()), $recordInfo);

        if (empty($records)) {
            throw new RecordNotFound($recordInfo->getRecordName());
        }

        return $this->createDORecord(reset($records), $recordInfo->getDomain());
    }

    public function getDomainRecords(string $domain): array
    {
        $uri = sprintf('domains/%s/records', $domain);

        $response = $this->DOApiClient->request('GET', $uri, []);
        $records = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return array_key_exists('domain_records', $records) ? $records['domain_records']: [];
    }

    private function filterRecords(array $records, RecordInfo $recordInfo): array
    {
        return array_filter($records, function (array $record) use ($recordInfo) {
            return $this->isValidRecord($record)
                    && $record['type'] === $recordInfo->getType()
                    && $record['name'] === $recordInfo->getRecordName();
        });
    }

    private function isValidRecord(array $record): bool
    {
        return array_key_exists('name', $record)
            && array_key_exists('type', $record)
            && array_key_exists('id', $record)
            && array_key_exists('data', $record);
    }

    private function createDORecord(array $data, string $domain): DORecord
    {
        return new DORecord(
            trim((string) $data['id']),
            trim($data['data']),
            $domain
        );
    }
}
