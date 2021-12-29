<?php

namespace PyrobyteWeb\Sms\DTO;

class SmsResponse
{
    private string $status;
    private string $statusCode;
    private string $smsId;
    private string $cost;
    private string $value;
    private string $statusText;

    public function __construct(
        string $status,
        string $statusCode,
        string $smsId = '',
        string $cost = '',
        string $value = '',
        string $statusText = ''
    ) {
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->smsId = $smsId;
        $this->cost = $cost;
        $this->value = $value;
        $this->statusText = $statusText;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @return string|null
     */
    public function getSmsId(): ?string
    {
        return $this->smsId;
    }

    /**
     * @return string|null
     */
    public function getCost(): ?string
    {
        return $this->cost;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getStatusText(): string
    {
        return $this->statusText;
    }
}
