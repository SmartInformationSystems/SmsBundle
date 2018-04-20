<?php
namespace SmartInformationSystems\SmsBundle\Transport\Request;

class SmscRequest extends AbstractRequest
{
    /**
     * Строковое представление для логирования или отправки GET.
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
