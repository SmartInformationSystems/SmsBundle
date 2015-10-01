<?php

namespace SmartInformationSystems\SmsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;

use SmartInformationSystems\SmsBundle\Entity\Sms as SmsEntity;

/**
 * Класс для отправки смс.
 *
 */
class Sms
{
    private $container;
    private $templating;

    public function __construct(ContainerInterface $container, EngineInterface $templating)
    {
        $this->container = $container;
        $this->templating = $templating;
    }

    /**
     * Отправка смс.
     *
     * @param string $phone Кому
     * @param string $template Шаблон
     * @param array $templateVars Переменные шаблона
     * @param string $fromName От кого
     *
     * @return int
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

        return $sms->getId();
    }
}
