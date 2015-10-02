<?php

namespace SmartInformationSystems\SmsBundle\Transport\Response;

abstract class AbstractResponse
{
    /**
     * Тип запроса (баланс, отправка, получение статуса).
     *
     * @var string
     */
    private $requestType;

    /**
     * Исходный ответ.
     *
     * @var string
     */
    private $rawResponse;

    /**
     * Распарсенный ответ.
     *
     * @var mixed
     */
    private $data;

    /**
     * Конструктор.
     *
     * @param string $requestType Тип запрос
     * @param string $rawResponse Исходный ответ
     */
    public function __construct($requestType, $rawResponse)
    {
        $this->requestType = $requestType;
        $this->rawResponse = $rawResponse;
        $this->data = $this->parse($this->rawResponse);
    }

    /**
     * Возвращает тип запроса.
     *
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * Возвращает данные.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function __toString()
    {
        return $this->rawResponse;
    }

    /**
     * Парсинг ответа.
     *
     * @param string $rawResponse
     *
     * @return mixed
     */
    abstract protected function parse($rawResponse);

    /**
     * Успех или нет.
     *
     * @return bool
     */
    abstract public function isSuccess();

    /**
     * Идентификатор смс в транспорте.
     *
     * @return string
     */
    abstract public function getExternalId();

    /**
     * Ошибка.
     *
     * @return string
     */
    abstract public function getError();

    /**
     * Возвращает баланс, если был запрос баланса.
     *
     * @return double
     */
    abstract public function getBalance();

    /**
     * Возвращает статус доставки, если был запрос статуса.
     *
     * @return double
     */
    abstract public function getDeliveryStatus();
}
