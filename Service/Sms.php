<?php

namespace SmartInformationSystems\SmsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;

use SmartInformationSystems\SmsBundle\Transport\AbstractTransport;
use SmartInformationSystems\SmsBundle\Transport\TransportFactory;
use SmartInformationSystems\SmsBundle\Transport\ConfigurationContainer;
use SmartInformationSystems\SmsBundle\Entity\Sms as SmsEntity;

/**
 * Сервис для отправки смс.
 *
 */
class Sms
{
    /**
     * @var AbstractTransport
     */
    private $transport;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * От кого отправлять сообщения.
     *
     * @var string
     */
    private $defaultFrom;

    /**
     * Конструктор.
     *
     * @param ConfigurationContainer $configuration
     * @param ContainerInterface $container
     * @param EngineInterface $templating
     *
     * @throws
     */
    public function __construct(ConfigurationContainer $configuration, ContainerInterface $container, EngineInterface $templating)
    {
        $this->container = $container;
        $this->templating = $templating;

        $this->defaultFrom = $configuration->getFrom();

        $this->transport = TransportFactory::create(
            $configuration,
            $this->container->get('doctrine')->getEntityManager()
        );
    }

    /**
     * Отправка смс.
     *
     * @param string $phone Кому
     * @param string $template Шаблон
     * @param array $templateVars Переменные шаблона
     * @param string $fromName От кого
     *
     * @return SmsEntity
     *
     * @throws
     */
    public function send($phone, $template, array $templateVars = [], $fromName = '')
    {
        $sms = new SmsEntity();
        $sms
            ->setTransport($this->getTransport()->getName())
            ->setFromName($fromName ? $fromName : $this->defaultFrom)
            ->setPhone($phone)
            ->setMessage(
                $this->templating->render(
                    $template,
                    $templateVars
                )
            );

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($sms);
        $em->flush($sms);

        return $sms;
    }

    /**
     * Возвращает траспорт.
     *
     * @return AbstractTransport
     */
    public function getTransport()
    {
        return $this->transport;
    }
}
