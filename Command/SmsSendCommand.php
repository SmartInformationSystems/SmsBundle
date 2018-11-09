<?php
namespace SmartInformationSystems\SmsBundle\Command;

use SmartInformationSystems\SmsBundle\Repository\SmsRepository;
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
     * @var AbstractTransport
     */
    private $transport;

    /**
     * @var SmsRepository
     */
    private $repository;

    /**
     * @var int
     */
    private $limit;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sis_sms:send')
            ->setDescription('Sending sms from queue')
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Messages limit for sending', self::LIMIT_DEFAULT)
        ;
    }

    /**
     * @inheritdoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->limit = $input->getOption('limit');
        $this->transport = $this->getContainer()->get('sis_sms')->getTransport();
        $this->repository = $this->getContainer()->get('doctrine')->getRepository(Sms::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queue = $this->repository->getForSending($this->limit);

        foreach ($queue as $sms) {
            try {
                $this->transport->send($sms);
            } catch (NotAllowedPhoneTransportException $e) {
            }
        }
    }
}
