<?php
namespace SmartInformationSystems\SmsBundle\Transport;

use SmartInformationSystems\SmsBundle\Entity\Sms;
use SmartInformationSystems\SmsBundle\Transport\Request\AbstractRequest;
use SmartInformationSystems\SmsBundle\Transport\Response\DummyResponse;

class DummyTransport extends AbstractTransport
{
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
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Sms $sms)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function status(Sms $sms)
    {
        return 'delivered';
    }

    /**
     * {@inheritdoc}
     */
    protected function createResponse(AbstractRequest $request, $rawResponse)
    {
        return new DummyResponse($request->getType(), $rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'dummy';
    }
}
