<?php

namespace SmartInformationSystems\SmsBundle\Transport;

use SmartInformationSystems\SmsBundle\Exception\TransportException;

/**
 * Настройки транспорта.
 *
 */
class ConfigurationContainer
{
    /**
     * Настройки.
     *
     * @var array
     */
    private $config = array();

    /**
     * Конструктор.
     *
     */
    public function __construct()
    {
    }

    /**
     * Установка конфига.
     *
     * @param array $config Конфиг
     *
     * @return ConfigurationContainer
     *
     * @throws TransportException
     */
    public function setConfig(array $config)
    {
        if (empty($config)) {
            throw new TransportException('Нет настроек транспорта');
        }

        $this->config = $config;

        return $this;
    }

    /**
     * Возвращает "от кого" для сообщений.
     *
     * @return string
     */
    public function getFrom()
    {
        return !empty($this->config['from']) ? $this->config['from'] : '';
    }

    /**
     * Возвращает тип транспорта.
     *
     * @return string
     */
    public function getTransportType()
    {
        return $this->config['transport']['type'];
    }

    /**
     * Возвращает настройки транспорта.
     *
     * @return array
     */
    public function getTransportParams()
    {
        return $this->config['transport']['params'];
    }
}
