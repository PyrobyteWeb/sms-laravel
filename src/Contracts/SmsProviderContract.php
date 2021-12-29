<?php

namespace PyrobyteWeb\Sms\Contracts;

use PyrobyteWeb\Sms\DTO\SmsSendDTO;

interface SmsProviderContract
{
    public function send(SmsSendDTO $data);
    public function getStatus(string $smsId);
    public function getBalance();
}
