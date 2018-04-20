<?php
namespace SmartInformationSystems\SmsBundle\Transport\Response;

use SmartInformationSystems\SmsBundle\Transport\AbstractTransport;
use Symfony\Component\Intl\Exception\NotImplementedException;

class SmscResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    protected function parse($rawResponse)
    {
        return json_decode($rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        return $this->getData()->id;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        $data = $this->getData();

        return empty($data->error);
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
        return $this->getData()->error;
    }
}
