<?php

namespace SmartInformationSystems\SmsBundle\Transport;

use SmartInformationSystems\SmsBundle\Entity\Sms;
use SmartInformationSystems\SmsBundle\Transport\Request\AbstractRequest;
use SmartInformationSystems\SmsBundle\Transport\Request\SmscRequest;
use SmartInformationSystems\SmsBundle\Transport\Response\SmscResponse;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

/**
 * Отправка смс через 01sms (http://01sms.ru).
 *
 */
class SmscTransport extends AbstractTransport
{
    /**
     * Адрес API.
     *
     * @var string
     */
    const API_URL = 'http://smsc.ru/sys';

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        throw new MethodNotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    public function send(Sms $sms)
    {
        $params = array(
            'phones' => '7' . $sms->getPhone(),
            'mes' => $sms->getMessage(),
            'charset' => 'utf-8',
            'cost' => 0,
            'fmt' => 3,
        );

        if ($sms->getFromName()) {
            $params['sender'] = $sms->getFromName();
        }

        return $this->doPostRequest(
            $this->prepareRequest(
                AbstractTransport::REQUEST_TYPE_SEND,
                $params
            ),
            $sms
        );
    }

    /**
     * {@inheritdoc}
     */
    public function status(Sms $sms)
    {
        throw new MethodNotImplementedException(__METHOD__);
    }

    /**
     * Подготавливает строку запроса.
     *
     * @param string $type Тип запроса
     * @param string $path
     * @param array $params
     *
     * @return SmscRequest
     *
     * @throws
     */
    private function prepareRequest($type, array $params = [])
    {
        return new SmscRequest(
            $type,
            self::API_URL . '/send.php?' . http_build_query(array_merge(
                [
                    'login' => $this->getParam('username'),
                    'psw' => $this->getParam('password'),
                ],
                $params
            ))
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function createResponse(AbstractRequest $request, $rawResponse)
    {
        return new SmscResponse($request->getType(), $rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'smsc';
    }
}
