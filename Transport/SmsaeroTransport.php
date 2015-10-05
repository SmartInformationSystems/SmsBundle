<?php

namespace SmartInformationSystems\SmsBundle\Transport;

use SmartInformationSystems\SmsBundle\Entity\Sms;
use SmartInformationSystems\SmsBundle\Transport\Request\AbstractRequest;
use SmartInformationSystems\SmsBundle\Transport\Request\SmsaeroRequest;
use SmartInformationSystems\SmsBundle\Transport\Response\SmsaeroResponse;

/**
 * Отправка смс через smsaero (http://smsaero.ru/).
 *
 */
class SmsaeroTransport extends AbstractTransport
{
    /**
     * Адрес API.
     *
     * @var string
     */
    const API_URL = 'http://gate.smsaero.ru';

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
        $result = $this->doGetRequest(
            $this->prepareRequest(
                AbstractTransport::REQUEST_TYPE_BALANCE,
                '/balance/'
            )
        );

        return $result->getBalance();
    }

    /**
     * {@inheritdoc}
     */
    public function send(Sms $sms)
    {
        $params = array(
            'to' => '7' . $sms->getPhone(),
            'text' => $sms->getMessage(),
        );

        if ($sms->getFromName()) {
            $params['from'] = $sms->getFromName();
        }

        return $this->doGetRequest(
            $this->prepareRequest(
                AbstractTransport::REQUEST_TYPE_SEND,
                '/send/',
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
        $result = $this->doGetRequest(
            $this->prepareRequest(
                AbstractTransport::REQUEST_TYPE_STATUS,
                '/status/',
                array(
                    'id' => $sms->getExternalId(),
                )
            ),
            $sms
        );

        return $result->getDeliveryStatus();
    }

    /**
     * Подготавливает строку запроса.
     *
     * @param string $type Тип запроса
     * @param string $path
     * @param array $params
     *
     * @return SmsaeroRequest
     */
    private function prepareRequest($type, $path, array $params = array())
    {
        return new SmsaeroRequest(
            $type,
            self::API_URL . $path,
            array_merge(
                $params,
                array(
                    'answer' => 'json',
                    'user' => $this->getParam('username'),
                    'password' => $this->getParam('password'),
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function createResponse(AbstractRequest $request, $rawResponse)
    {
        return new SmsaeroResponse($request->getType(), $rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'smsaero';
    }
}
