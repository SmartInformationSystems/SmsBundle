<?php
namespace SmartInformationSystems\SmsBundle\Transport\Response;

class DummyResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    protected function parse($rawResponse)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return true;
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
    public function getDeliveryStatus()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        return '';
    }
}
