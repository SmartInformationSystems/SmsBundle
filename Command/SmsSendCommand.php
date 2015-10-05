<?php

namespace SmartInformationSystems\SmsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use SmartInformationSystems\SmsBundle\Entity\Sms;
use SmartInformationSystems\SmsBundle\Transport\AbstractTransport;

use SmartInformationSystems\SmsBundle\Exception\NotAllowedPhoneTransportException;

class SmsSendCommand extends ContainerAwareCommand
{
    const LIMIT_DEFAULT = 100;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sis_sms:send')
            ->setDescription('Sending sms from queue')
            ->addOption('limit', 100, InputOption::VALUE_OPTIONAL, 'Messages limit for sending')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getOption('limit') ? $input->getOption('limit') : self::LIMIT_DEFAULT;

        /** @var AbstractTransport $transport */
        $transport = $this->getContainer()->get('sis_sms')->getTransport();

        /** @var Sms[] $queue */
        $queue = $this->getContainer()->get('doctrine')->getRepository(
            'SmartInformationSystems\SmsBundle\Entity\Sms'
        )->getForSending($limit);

        foreach ($queue as $sms) {
            try {
                $transport->send($sms);
            } catch (NotAllowedPhoneTransportException $e) {
            }
        }
    }
}