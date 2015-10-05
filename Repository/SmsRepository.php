<?php

namespace SmartInformationSystems\SmsBundle\Repository;

use Doctrine\ORM\EntityRepository;

use SmartInformationSystems\SmsBundle\Entity\Sms;

class SmsRepository extends EntityRepository
{
    /**
     * Возвращает сообщения для отправки.
     *
     * @param int $limit
     *
     * @return Sms
     */
    public function getForSending($limit)
    {
        $builder = $this->createQueryBuilder('s');

        $builder->andWhere('s.isSent = FALSE');
        $builder->setMaxResults($limit);
        $builder->addOrderBy('s.createdAt', 'DESC');

        return $builder->getQuery()->getResult();
    }
}
