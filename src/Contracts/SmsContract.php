<?php

namespace PyrobyteWeb\Sms\Contracts;

interface SmsContract
{
    public function init(SmsProviderContract $provider);
}
