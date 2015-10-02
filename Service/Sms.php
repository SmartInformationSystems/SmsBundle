<?php

namespace SmartInformationSystems\SmsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;

use \SmartInformationSystems\SmsBundle\Transport\AbstractTransport;
use SmartInformationSystems\SmsBundle\Transport\TransportFactory;
use SmartInformationSystems\SmsBundle\Transport\ConfigurationContainer;
use SmartInformationSystems\SmsBundle\Entity\Sms as SmsEntity;

/**
 * Класс для отправки смс.
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
     * Конструктор.
     *
     * @param ConfigurationContainer $configuration
     * @param ContainerInterface $container
     * @param EngineInterface $templating
     */
    public function __construct(ConfigurationContainer $configuration, ContainerInterface $container, EngineInterface $templating)
    {
        $this->container = $container;
        $this->templating = $templating;

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
     * @return Sms
     */
    public function send($phone, $template, array $templateVars = array(), $fromName = '')
    {
        $sms = new SmsEntity();
        $sms
            ->setFromName($fromName)
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
