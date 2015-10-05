<?php

namespace SmartInformationSystems\SmsBundle\Transport;

use Doctrine\ORM\EntityManager;

use SmartInformationSystems\SmsBundle\Entity\Sms;
use SmartInformationSystems\SmsBundle\Entity\SmsRequestLog;
use SmartInformationSystems\SmsBundle\Exception\UnknownTransportParameterException;
use SmartInformationSystems\SmsBundle\Transport\Request\AbstractRequest;
use SmartInformationSystems\SmsBundle\Transport\Response\AbstractResponse;

/**
 * Абстрактный класс транспорта.
 *
 */
abstract class AbstractTransport
{
    const REQUEST_TYPE_BALANCE = 'balance';

    const REQUEST_TYPE_SEND = 'send';

    const REQUEST_TYPE_STATUS = 'status';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Параметры.
     *
     * @var array
     */
    private $parameters = array();

    /**
     * Конструктор.
     *
     * @param array $params Параметры
     */
    public function __construct(EntityManager $em, array $params = array())
    {
        $this->em = $em;
        $this->parameters = $params;

        $this->init();
    }

    /**
     * Инициализация.
     *
     * @return void
     */
    protected function init()
    {
    }

    /**
     * Возвращает параметр.
     *
     * @param string $name Имя параметра
     *
     * @return mixed
     *
     * @throws UnknownTransportParameterException
     */
    protected function getParam($name)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        throw new UnknownTransportParameterException('Неизвестный параметр: ' . $name);
    }

    /**
     * Отправка запроса методом GET.
     *
     * @param AbstractRequest $request
     * @param Sms $sms
     *
     * @return AbstractResponse
     */
    protected function doGetRequest(AbstractRequest $request, Sms $sms = NULL)
    {
        $log = new SmsRequestLog();
        $log->setTransport($this->getName());
        $log->setRequest($request->__toString());
        $log->setRequestAt(new \DateTime());
        if ($sms) {
            $log->setSms($sms);
        }

        try {
            $this->em->persist($log);
            $this->em->flush($log);

            $response = $this->createResponse(
                $request,
                file_get_contents($request)
            );

            $log->setResponse($response->__toString());
            $log->setResponseAt(new \DateTime());
            $this->em->persist($log);
            $this->em->flush($log);

            switch ($request->getType()) {
                case self::REQUEST_TYPE_SEND:
                    if ($response->isSuccess()) {
                        $sms->setLastError(NULL);
                        $sms->setExternalId($response->getExternalId());
                        $sms->setIsSent(TRUE);
                        $sms->setSentAt(new \DateTime());
                    } else {
                        $sms->setLastError($response->getError());
                    }
                    $this->em->persist($log);
                    $this->em->flush($log);
                    break;
            }

            return $response;

        } catch (\Exception $e) {
            $sms->setLastError($e->getMessage());
            $log->setResponse($e->__toString());
            $log->setResponseAt(new \DateTime());
            $this->em->persist($sms);
            $this->em->flush($sms);
            $this->em->persist($log);
            $this->em->flush($log);

            return NULL;
        }
    }

    /**
     * Возвращает остаток баланса.
     *
     * @return double
     */
    abstract public function getBalance();

    /**
     * Отправка сообщения.
     *
     * @param Sms $sms
     *
     * @return bool
     */
    abstract public function send(Sms $sms);

    /**
     * Статус сообщения.
     *
     * @param Sms $sms
     *
     * @return bool
     */
    abstract public function status(Sms $sms);

    /**
     * Создает объект ответа.
     *
     * @param AbstractRequest $request
     * @param string $rawResponse
     *
     * @return AbstractResponse
     */
    abstract protected function createResponse(AbstractRequest $request, $rawResponse);

    /**
     * Возвращает название транспорта.
     *
     * @return string
     */
    abstract public function getName();
}
