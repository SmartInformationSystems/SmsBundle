<?php

namespace SmartInformationSystems\SmsBundle\Transport;

use SmartInformationSystems\SmsBundle\Entity\Sms;
use SmartInformationSystems\SmsBundle\Transport\Request\AbstractRequest;
use SmartInformationSystems\SmsBundle\Transport\Request\Sms01Request;
use SmartInformationSystems\SmsBundle\Transport\Response\Sms01Response;
use Symfony\Component\Intl\Exception\MethodNotImplementedException;

/**
 * Отправка смс через 01sms (http://01sms.ru).
 *
 */
class Sms01Transport extends AbstractTransport
{
    /**
     * Адрес API.
     *
     * @var string
     */
    const API_URL = 'http://info.01sms.ru/xml/';

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
            'to' => '7' . $sms->getPhone(),
            'text' => $sms->getMessage(),
        );

        if ($sms->getFromName()) {
            $params['from'] = $sms->getFromName();
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
     * @return Sms01Request
     *
     * @throws
     */
    private function prepareRequest($type, array $params = array())
    {
        return new Sms01Request(
            $type,
            self::API_URL,
            array_merge(
                $params,
                array(
                    'answer' => 'json',
                    'login' => $this->getParam('username'),
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
        return new Sms01Response($request->getType(), $rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '01sms';
    }
}
