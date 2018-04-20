<?php
namespace SmartInformationSystems\SmsBundle\Transport;

use Doctrine\ORM\EntityManager;

use SmartInformationSystems\SmsBundle\Entity\Sms;
use SmartInformationSystems\SmsBundle\Entity\SmsRequestLog;
use SmartInformationSystems\SmsBundle\Transport\Request\AbstractRequest;
use SmartInformationSystems\SmsBundle\Transport\Response\AbstractResponse;

use SmartInformationSystems\SmsBundle\Exception\UnknownTransportParameterException;
use SmartInformationSystems\SmsBundle\Exception\NotAllowedPhoneTransportException;

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
     *
     * @throws
     */
    protected function doGetRequest(AbstractRequest $request, Sms $sms = NULL)
    {
        if (
            $sms
            && !empty($this->getParam('allowed_phones'))
            && !in_array($sms->getPhone(), $this->getParam('allowed_phones'))
        ) {
            $sms->setIsSent(TRUE);
            $sms->setLastError('not_allowed_phone');
            $this->em->persist($sms);
            $this->em->flush($sms);

            throw new NotAllowedPhoneTransportException($sms->getPhone());
        }

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
                    $this->em->flush($sms);
                    break;
            }

            return $response;

        } catch (\Exception $e) {
            $sms->setLastError($e->getMessage());
            $log->setResponse($e->__toString());
            $log->setResponseAt(new \DateTime());
            $this->em->persist($log);
            $this->em->flush($sms);
            $this->em->flush($log);

            return NULL;
        }
    }

    /**
     * @param AbstractRequest $request
     * @param Sms $sms
     *
     * @return AbstractResponse
     *
     * @throws
     */
    protected function doPostRequest(AbstractRequest $request, Sms $sms = null)
    {
        if (
            $sms
            && !empty($this->getParam('allowed_phones'))
            && !in_array($sms->getPhone(), $this->getParam('allowed_phones'))
        ) {
            $sms->setTransport($this->getName());
            $sms->setIsSent(true);
            $sms->setLastError('not_allowed_phone');
            $this->em->persist($sms);
            $this->em->flush($sms);

            throw new NotAllowedPhoneTransportException($sms->getPhone());
        }

        $sms->setTransport($this->getName());

        $log = new SmsRequestLog();
        $log->setTransport($this->getName());
        $log->setRequest($request->__toString());
        $log->setRequestAt(new \DateTime());
        if ($sms) {
            $log->setSms($sms);
        }
        $this->em->persist($log);

        try {
            $this->em->flush($log);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: text/xml; charset=utf-8']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CRLF, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->__toString());
            curl_setopt($ch, CURLOPT_URL, $request->getUrl());
            $result = curl_exec($ch);
            curl_close($ch);

            $response = $this->createResponse($request, $result);

            $log->setResponse($response->__toString());
            $log->setResponseAt(new \DateTime());
            $this->em->persist($log);
            $this->em->flush($log);

            switch ($request->getType()) {
                case self::REQUEST_TYPE_SEND:
                    if ($sms) {
                        if ($response->isSuccess()) {
                            $sms->setLastError(NULL);
                            $sms->setExternalId($response->getExternalId());
                            $sms->setIsSent(TRUE);
                            $sms->setSentAt(new \DateTime());
                        } else {
                            $sms->setLastError($response->getError());
                        }
                        $this->em->flush($sms);
                    }
                    break;
            }

            return $response;

        } catch (\Exception $e) {
            if ($sms) {
                $sms->setLastError($e->getMessage());
                $this->em->flush($sms);
            }
            $log->setResponse($e->__toString());
            $log->setResponseAt(new \DateTime());
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
     *
     * @throws
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
