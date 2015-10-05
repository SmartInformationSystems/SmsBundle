<?php

namespace SmartInformationSystems\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Смс в очереди на отправку.
 *
 * @ORM\Entity(repositoryClass="SmartInformationSystems\SmsBundle\Repository\SmsRepository")
 * @ORM\Table(
 *   name="sis_sms",
 *   indexes={
 *     @ORM\Index(name="i_sent", columns={"is_sent"}),
 *     @ORM\Index(name="i_phone", columns={"phone"})
 *   },
 *   uniqueConstraints={
 *     @ORM\UniqueConstraint(name="ui_transport_external_id", columns={"transport", "external_id"})
 *   }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Sms
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
     * Транспорт.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $transport;

    /**
     * Идентификатор в транспорте.
     *
     * @var string
     *
     * @ORM\Column(name="external_id", type="string", length=255, nullable=true)
     */
    private $externalId;

    /**
     * Телефон получателя.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * Сообщение.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $message;

    /**
     * Имя отправителя.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, name="from_name")
     */
    private $fromName;

    /**
     * Отправлено ли.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_sent")
     */
    private $isSent;

    /**
     * Последняя ошибка.
     *
     * @var string
     *
     * @ORM\Column(name="last_error", type="string", length=255, nullable=true)
     */
    private $lastError;

    /**
     * Дата отправки.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="sent_at", type="datetime", nullable=true)
     */
    private $sentAt;

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
        $this->isSent = FALSE;
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
     * Устанавливает транспорт.
     *
     * @param string $transport
     *
     * @return Sms
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
     * Устанавливает телефон получателя.
     *
     * @param string $phone Телефон получателя
     *
     * @return Sms
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Возвращает телефон получателя.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Устанавливает идентификатор в транспорте.
     *
     * @param string $externalId
     *
     * @return Sms
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Возвращает идентификатор в транспорте.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }
    /**
     * Устанавливает сообщение.
     *
     * @param string $message Тема
     *
     * @return Sms
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Возвращает сообщение.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Устанавливает имя отправителя.
     *
     * @param string $fromName Имя отправителя
     *
     * @return Sms
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Возвращает имя отправителя.
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Устанавливает отправлено ли смс.
     *
     * @param boolean $isSent
     *
     * @return Sms
     */
    public function setIsSent($isSent)
    {
        $this->isSent = $isSent;

        return $this;
    }

    /**
     * Возвращает отправлено ли смс.
     *
     * @return boolean
     */
    public function getIsSent()
    {
        return $this->isSent;
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
     * Устанавливает дату отправки.
     *
     * @param \DateTime $sentAt
     *
     * @return Sms
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Возвращает дату отправки.
     *
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
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

    /**
     * Устанавливает последнюю ошибку.
     *
     * @param string $lastError
     *
     * @return Sms
     */
    public function setLastError($lastError)
    {
        $this->lastError = $lastError;

        return $this;
    }

    /**
     * Возвращает последнюю ошибку.
     *
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }
}
