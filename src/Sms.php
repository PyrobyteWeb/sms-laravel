<?php

namespace PyrobyteWeb\Sms;

use PyrobyteWeb\Sms\Contracts\SmsDTOContract;
use PyrobyteWeb\Sms\Contracts\SmsProviderContract;
use PyrobyteWeb\Sms\Exceptions\InitProviderException;
use PyrobyteWeb\Sms\Providers\SmsRu;

class Sms
{
    /**
     * @var SmsProviderContract|mixed|string
     */
    private SmsProviderContract $provider;

    /**
     * Список поддерживаемых провайдеров
     * @var array|string[]
     */
    private array $providers = [
        'smsru' => SmsRu::class,
    ];

    /**
     * @throws InitProviderException
     */
    public function __construct()
    {
        if (!isset($this->providers[config('sms.provider')])) {
            throw new InitProviderException('Прайвайдер задан неверно');
        }

        $providerInstance = new $this->providers[config('sms.provider')];

        if ($providerInstance instanceof SmsProviderContract == false) {
            throw new InitProviderException('Прайвайдер не пренадлежит интерфейсу SmsProviderContract');
        }

        $this->provider = new $this->providers[config('sms.provider')];
    }

    public function send(SmsDTOContract $data)
    {
        return $this->provider->send($data);
    }

    public function getStatus(string $smsId)
    {
        return $this->provider->getStatus($smsId);
    }

    public function getBalance()
    {
        return $this->provider->getBalance();
    }
}
