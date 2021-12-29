<?php

namespace PyrobyteWeb\Sms\DTO;

use PyrobyteWeb\Sms\Contracts\SmsDTOContract;

class SmsGetStatusDTO implements SmsDTOContract
{
    private string $smsId;

    public function __construct(string $smsId)
    {
        $this->smsId = $smsId;
    }

    public function toArray(): array
    {
        return [
            'sms_id' => $this->smsId,
        ];
    }
}
