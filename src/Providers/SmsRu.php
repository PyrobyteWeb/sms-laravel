<?php

namespace PyrobyteWeb\Sms\Providers;

use PyrobyteWeb\Sms\Contracts\SmsDTOContract;
use PyrobyteWeb\Sms\Contracts\SmsProviderContract;
use PyrobyteWeb\Sms\DTO\SmsResponse;
use PyrobyteWeb\Sms\DTO\SmsGetStatusDTO;
use PyrobyteWeb\Sms\DTO\SmsSendDTO;
use PyrobyteWeb\Sms\Exceptions\SmsRuResponseException;

class SmsRu implements SmsProviderContract
{
    private string $apiKey;
    private string $protocol;
    private string $domain;
    private int $retryCounts;

    function __construct()
    {
        $this->apiKey = config('sms.smsru.key');
        $this->protocol = config('sms.smsru.protocol');
        $this->domain = config('sms.smsru.domain');
        $this->retryCounts = config('sms.smsru.retry_counts');
    }

    /**
     * Отправить сообщение
     *
     * @param SmsSendDTO $data
     * @return SmsResponse
     * @throws SmsRuResponseException
     */
    public function send(SmsSendDTO $data): SmsResponse
    {
        $url = $this->getUrl('/sms/send');
        $request = $this->request($url, $data);
        $response = $this->checkReplyError($request, 'send');

        $temp = (array)$response->sms;
        unset($response->sms);
        $temp = array_pop($temp);

        return new SmsResponse($temp->status, $temp->status_code, $temp->sms_id, $temp->cost);
    }

    /**
     * Проверить статус сообщения
     *
     * @param string $smsId
     * @throws SmsRuResponseException
     * @return SmsResponse
     */
    public function getStatus(string $smsId): SmsResponse
    {
        $request = $this->request($this->getUrl('/sms/status'), new SmsGetStatusDTO($smsId));
        $response = $this->checkReplyError($request, 'getStatus');

        $temp = (array)$response->sms;
        unset($response->sms);
        $temp = array_pop($temp);

        return new SmsResponse($temp->status, $temp->status_code, cost: $temp->cost, statusText: $temp->status_text);
    }

    /**
     * Получить баланс
     *
     * @return SmsResponse
     * @throws SmsRuResponseException
     */
    public function getBalance(): SmsResponse
    {
        $request = $this->request($this->getUrl('/my/balance'));
        $response = $this->checkReplyError($request, 'getBalance');

        return new SmsResponse(
            $response->status,
            $response->status_code,
            value: $response->balance
        );
    }

    /**
     * Формирование запроса
     *
     * @param $url
     * @param SmsDTOContract|null $data
     * @return string
     */
    private function request($url, SmsDTOContract $data = null): string
    {
        $ch = curl_init($url . "?json=1");

        $requestData = [
            'api_id' => $this->apiKey,
        ];

        if (!is_null($data)) {
            $requestData = array_merge($requestData, $data->toArray());
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));

        $body = curl_exec($ch);

        if ($body === false) {
            $error = curl_error($ch);
        } else {
            $error = false;
        }

        curl_close($ch);

        if ($error && $this->retryCounts > 0) {
            $this->retryCounts--;
            return $this->Request($url, $data);
        }

        return $body;
    }

    /**
     * @param $response
     * @param string $action
     * @return object
     * @throws SmsRuResponseException
     */
    private function checkReplyError($response, string $action): object
    {
        if (!$response) {
            throw new SmsRuResponseException("Action: $action. Невозможно установить связь с сервером SMS.RU. Проверьте - правильно ли указаны DNS сервера в настройках вашего сервера (nslookup sms.ru), и есть ли связь с интернетом (ping sms.ru).");
        }

        $result = json_decode($response);

        if (!$result || !$result->status) {
            throw new SmsRuResponseException("Action: $action. Невозможно установить связь с сервером SMS.RU. Проверьте - правильно ли указаны DNS сервера в настройках вашего сервера (nslookup sms.ru), и есть ли связь с интернетом (ping sms.ru).");
        }

        if ($result->status !== 'OK') {
            throw new SmsRuResponseException(sprintf("Action: %s. %s", $action, $result->status_text));
        }

        return $result;
    }

    /**
     * @param string $path
     * @return string
     */
    private function getUrl(string $path): string
    {
        return $this->protocol . '://' . $this->domain . $path;
    }
}
