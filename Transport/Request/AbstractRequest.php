<?php

namespace SmartInformationSystems\SmsBundle\Transport\Request;

abstract class AbstractRequest
{
    /**
     * Тип запроса (баланс, отправка, получение статуса).
     *
     * @var string
     */
    private $type;
    /**
     * Адрес запроса.
     *
     * @var string
     */
    private $url;

    /**
     * Параметры запроса.
     *
     * @var array
     */
    private $params;

    /**
     * Конструктор.
     *
     * @param string $type Тип запроса
     * @param string $url Адрес запроса
     * @param array $params Параметры запроса
     */
    public function __construct($type, $url, array $params = array())
    {
        $this->type = $type;
        $this->url = $url;
        $this->params = $params;
    }

    /**
     * Возвращает тип запроса.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Строковое представление для логирования или отправки GET.
     *
     * @return string
     */
    public function __toString()
    {
        $queryString = array();
        foreach ($this->params as $name => $value) {
            $queryString[] = $name . '=' . urlencode($value);
        }

        return $this->url . '?' . implode('&', $queryString);
    }
}
