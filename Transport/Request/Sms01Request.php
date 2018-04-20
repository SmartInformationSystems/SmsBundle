<?php
namespace SmartInformationSystems\SmsBundle\Transport\Request;

use SmartInformationSystems\SmsBundle\Transport\AbstractTransport;

class Sms01Request extends AbstractRequest
{
    /**
     * Строковое представление для логирования или отправки GET.
     *
     * @return string
     */
    public function __toString()
    {
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n<request></request>");
        $security = $xml->addChild('security');
        $security->addChild('login')->addAttribute('value', $this->params['login']);
        $security->addChild('password')->addAttribute('value', $this->params['password']);

        if ($this->getType() == AbstractTransport::REQUEST_TYPE_SEND) {
            $message = $xml->addChild('message');
            $message->addAttribute('type', 'sms');
            $message->addChild('sender', $this->params['from']);
            $message->addChild('text', $this->params['text']);
            $message->addChild('phone')->addAttribute('cell', $this->params['to']);

            $abonent = $message->addChild('abonent');
            $abonent->addAttribute('phone', $this->params['to']);
            $abonent->addAttribute('number_sms', '1');
        }

        return $xml->asXML();
    }
}
