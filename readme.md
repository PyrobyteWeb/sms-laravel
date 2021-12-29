# Cron Tasks Database Laravel
Laravel 8+  
PHP 7.4+  

1. В файле app.php, в секцию packages добавить:
   \PyrobyteWeb\Sms\SmsServiceProvider::class
2. ``php artisan vendor:publish --provider="PyrobyteWeb\Sms\SmsServiceProvider"``
3. Добавить в ``.env`` параметр ``SMS_RU_KEY`` и указать свой токен от SMS_RU

## Доступные методы  
Работа осуществляется через фасад ``PyrobyteWeb\Sms\Facades\Sms``  
 - Метод отправки сообщения - ``send(SmsSendDTO $data): SmsResponse``
 - Метод получения статуса отправки сообщения - ``getStatus(string $smsId): SmsResponse`` 
 - Метод получения баланса - ``getBalance(): SmsResponse``  

### Пример использование
`````
\PyrobyteWeb\Sms\Facades\Sms::send(new \PyrobyteWeb\Sms\DTO\SmsSendDTO('71234567890', 'Тест'));  
\PyrobyteWeb\Sms\Facades\Sms::getStatus('123456-123456');  
\PyrobyteWeb\Sms\Facades\Sms::getBalance();  
`````
