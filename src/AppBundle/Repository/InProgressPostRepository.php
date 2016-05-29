<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class InProgressPostRepository extends EntityRepository
{
    function findInProgressPostsOrderByDate($offset) {
        return $this->createQueryBuilder('ip')
            ->select(array('p.id', 'p.content', 'ip.startedWorkAt as date', 'p.voteCount'))
            ->leftJoin('ip.post', 'p')
            ->orderBy('ip.startedWorkAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult();
    }

    public function findActiveInProgressPostsOrderByDate($offset)
    {
        return $this->createQueryBuilder('ip')
            ->select(array('p.id', 'p.content', 'ip.startedWorkAt as date', 'p.voteCount'))
            ->leftJoin('ip.post', 'p')
            ->orderBy('ip.startedWorkAt', 'DESC')
            ->where('ip.active = :active')
            ->setParameter('active', true)
            ->setFirstResult($offset)
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult();
    }
}
