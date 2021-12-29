<?php

namespace PyrobyteWeb\Sms\Facades;

use Illuminate\Support\Facades\Facade;
use PyrobyteWeb\Sms\DTO\SmsResponse;
use PyrobyteWeb\Sms\DTO\SmsSendDTO;

/**
 * @method static SmsResponse send(SmsSendDTO $data)
 * @method static SmsResponse getStatus(string $smsId)
 * @method static SmsResponse getBalance()
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor() { return 'sms'; }
}
