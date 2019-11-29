<?php

declare(strict_types=1);

namespace Console\Record;

use Console\IP\IPVersions;

final class RecordTypes
{
    public const A_TYPE = 'A';
    public const AAAA_TYPE = 'AAAA';

    public const RECORD_TYPE_IPV_MAPPING = [
      IPVersions::IP_V4 => self::A_TYPE,
      IPVersions::IP_V6 => self::AAAA_TYPE,
    ];
}
