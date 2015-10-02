<?php

namespace SmartInformationSystems\SmsBundle\Transport;

use Doctrine\ORM\EntityManager;

use SmartInformationSystems\SmsBundle\Exception\UnknownTransportException;

/**
 * Фабрика объектов траспорта.
 *
 */
class TransportFactory
{
    static $transports = array();

    /**
     * Создание объекта траспорта.
     *
     * @param ConfigurationContainer $config
     *
     * @return AbstractTransport
     *
     * @throws UnknownTransportException
     */
    public static function create(ConfigurationContainer $config, EntityManager $em)
    {
        if (empty(self::$transports[$config->getTransportType()])) {
            switch ($config->getTransportType()) {
                case 'smsaero':
                    self::$transports[$config->getTransportType()] = new SmsaeroTransport(
                        $em,
                        $config->getTransportParams()
                    );
                    break;
                default:
                    throw new UnknownTransportException($config->getTransportType());
            }
        }

        return self::$transports[$config->getTransportType()];
    }
}
