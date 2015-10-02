<?php

namespace SmartInformationSystems\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Лог обращения к транспорту.
 *
 * @ORM\Entity
 * @ORM\Table(name="sis_sms_request_log")
 * @ORM\HasLifecycleCallbacks()
 */
class SmsRequestLog
{
    /**
     * Идентификатор.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Смс.
     *
     * @var Sms
     *
     * @ORM\ManyToOne(targetEntity="Sms")
     * @ORM\JoinColumn(name="sms_id", referencedColumnName="id", nullable=true)
     */
    private $sms;

    /**
     * Транспорт.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $transport;

    /**
     * Запрос.
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $request;

    /**
     * Дата запроса.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="request_at", type="datetime", nullable=false)
     */
    private $requestAt;

    /**
     * Ответ.
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $response;

    /**
     * Дата ответа.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="response_at", type="datetime", nullable=true)
     */
    private $responseAt;

    /**
     * Дата создания.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Дата последнего изменения.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Конструктор.
     *
     */
    public function __construct()
    {
    }

    /**
     * Возвращает идентификатор.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Устанавливает смс.
     *
     * @param Sms $sms
     *
     * @return SmsRequestLog
     */
    public function setSms(Sms $sms = NULL)
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * Возвращает смс.
     *
     * @return Sms
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * Устанавливает транспорт.
     *
     * @param string $transport
     *
     * @return SmsRequestLog
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Возвращает транспорт.
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Устанавливает запрос.
     *
     * @param string $request
     *
     * @return SmsRequestLog
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Возвращает запрос.
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Устанавливает ответ.
     *
     * @param string $response
     *
     * @return SmsRequestLog
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Возвращает ответ.
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Устанавливает дату запроса.
     *
     * @param \DateTime $requestAt
     *
     * @return SmsRequestLog
     */
    public function setRequestAt($requestAt)
    {
        $this->requestAt = $requestAt;

        return $this;
    }

    /**
     * Возвращает дату запроса.
     *
     * @return \DateTime
     */
    public function getRequestAt()
    {
        return $this->requestAt;
    }

    /**
     * Устанавливает дату ответа.
     *
     * @param \DateTime $responseAt
     *
     * @return SmsRequestLog
     */
    public function setResponseAt($responseAt)
    {
        $this->responseAt = $responseAt;

        return $this;
    }

    /**
     * Возвращает дату ответа.
     *
     * @return \DateTime
     */
    public function getResponseAt()
    {
        return $this->responseAt;
    }

    /**
     * Устанавливает дату создания.
     *
     * @param \DateTime $createdAt
     *
     * @return Sms
     */
    private function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Возвращает дату создания.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Устанавливает дату последнего обновления.
     *
     * @param \DateTime $updatedAt Дата последнего обновления
     *
     * @return Sms
     */
    private function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Возвращает дату последнего обновления.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Автоматическая установка даты создания.
     *
     * @ORM\PrePersist
     */
    public function prePersistHandler()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Автоматическая установка даты обновления.
     *
     * @ORM\PreUpdate
     */
    public function preUpdateHandler()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
