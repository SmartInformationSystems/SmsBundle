<?php

namespace SmartInformationSystems\SmsBundle\Transport;

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
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
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
