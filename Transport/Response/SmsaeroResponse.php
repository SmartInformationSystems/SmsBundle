<?php

namespace SmartInformationSystems\SmsBundle\Transport\Response;

use SmartInformationSystems\SmsBundle\Exception\Smsaero\BadResponseSmsaeroTransportException;
use SmartInformationSystems\SmsBundle\Exception\Smsaero\SmsaeroTransportException;
use SmartInformationSystems\SmsBundle\Transport\AbstractTransport;

class SmsaeroResponse extends AbstractResponse
{
    const STATUS_SEND_ACCEPTED = 'accepted';
    const STATUS_SEND_REJECTED = 'reject';

    /**
     * {@inheritdoc}
     */
    protected function parse($rawResponse)
    {
        $json = json_decode($rawResponse);
        if ($json === NULL) {
            throw new BadResponseSmsaeroTransportException($rawResponse);
        }

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        $data = $this->getData();
        if (empty($data->result) || $data->result != self::STATUS_SEND_ACCEPTED) {
            throw new SmsaeroTransportException('Попытка получить идентификатор сообщения без успешной отправки');
        }

        return !empty($data->id) ? $data->id : NULL;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        $data = $this->getData();
        switch ($this->getRequestType()) {
            case AbstractTransport::REQUEST_TYPE_SEND:
                return !empty($data->result) && $data->result == self::STATUS_SEND_ACCEPTED;
        }

        return !empty($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        if (!$this->getRequestType() != AbstractTransport::REQUEST_TYPE_BALANCE) {
            throw new SmsaeroTransportException('Попытка получить баланс без запроса баланса');
        }

        return (double)$this->getData()->balance;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveryStatus()
    {
        if (!$this->getRequestType() != AbstractTransport::REQUEST_TYPE_STATUS) {
            throw new SmsaeroTransportException('Попытка получить статус доставки без запроса статуса');
        }

        return $this->getData()->result;
    }

    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        $data = $this->getData();
        if (
            $this->getRequestType() == AbstractTransport::REQUEST_TYPE_SEND
            && !empty($data->result)
            && $data->result == self::STATUS_SEND_REJECTED
        ) {
            return empty($data->reason) ? 'EMPTY ERROR' : $data->reason;
        }

        return '';
    }
}
