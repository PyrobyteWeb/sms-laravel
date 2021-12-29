<?php

namespace PyrobyteWeb\Sms\DTO;

use PyrobyteWeb\Sms\Contracts\SmsDTOContract;

class SmsSendDTO implements SmsDTOContract
{
    /**
     * Номер телефона получателя (либо несколько номеров, через запятую — до 100 штук за один запрос).
     * Если вы указываете несколько номеров и один из них указан неверно,
     * то на остальные номера сообщения также не отправляются, и возвращается код ошибки.
     */
    private string $to;

    /**
     * Текст сообщения в кодировке UTF-8
     */
    private string $msg;

    /**
     * array('номер получателя' => 'текст сообщения') - Если вы хотите в одном запросе отправить разные
     * сообщения на несколько номеров, то воспользуйтесь этим параметром (до 100 сообщений за 1 запрос).
     * В этом случае, параметры to и text использовать не нужно
     */
    private array $multi;

    /**
     * Имя отправителя (должно быть согласовано с администрацией).
     * Если не заполнено, в качестве отправителя будет указан ваш номер.
     */
    private string $from;

    /**
     * Если вам нужна отложенная отправка, то укажите время отправки.
     * Указывается в формате UNIX TIME (пример: 1280307978). Должно быть не больше 7 дней с момента подачи запроса.
     * Если время меньше текущего времени, сообщение отправляется моментально.
     */
    private int $time;

    /**
     * Переводит все русские символы в латинские. (по умолчанию 0)
     */
    private int $partnerId;

    /**
     * Имитирует отправку сообщения для тестирования ваших программ на правильность обработки ответов сервера.
     * При этом само сообщение не отправляется и баланс не расходуется. (по умолчанию 0)
     */
    private bool $test = false;

    /**
     * Если вы участвуете в партнерской программе,
     * укажите этот параметр в запросе и получайте проценты от стоимости отправленных сообщений.
     */
    private bool $translit = false;

    public function __construct(
        string $to,
        string $msg,
        array $multi = [],
        string $from = '',
        int $time = 0,
        int $partnerId = 0,
        bool $test = false,
        bool $translit = false,
    ) {
        $this->to = $to;
        $this->msg = $msg;
        $this->multi = $multi;
        $this->from = $from;
        $this->time = $time;
        $this->partnerId = $partnerId;
        $this->test = $test;
        $this->translit = $translit;
    }

    public function toArray(): array
    {
        $result = [];

        foreach (get_object_vars($this) as $var => $value) {
            if (empty($value)) continue;
            $result[$var] = $value;
        }

        return $result;
    }
}
