<?php
namespace SmartInformationSystems\SmsBundle\Transport\Response;

use SmartInformationSystems\SmsBundle\Transport\AbstractTransport;
use Symfony\Component\Intl\Exception\NotImplementedException;

class Sms01Response extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    protected function parse($rawResponse)
    {
        return new \SimpleXMLElement($rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        $data = $this->getData();
        foreach ($data->information as $information) {
            return $information['id_sms'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        $data = $this->getData();
        switch ($this->getRequestType()) {
            case AbstractTransport::REQUEST_TYPE_SEND:
                foreach ($data->information as $information) {
                    return (string)$information === 'send';
                }
                break;
            default:
                throw new NotImplementedException($this->getRequestType());
        }
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
        $data = $this->getData();
        foreach ($data->information as $information) {
            return (string)$information;
        }

        return '';
    }
}
