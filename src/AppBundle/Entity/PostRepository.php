<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PostRepository extends EntityRepository
{
    public function findAllOrderById(int $offset = 0)
    {
        return $this->findBy(
            array(),
            array('id' => 'DESC'),
            10,
            $offset
        );
    }

    public function findAllWithVotedOrderById(int $offset, User $user)
    {
        return $this
            ->createQueryBuilder('p')
            ->select(array('p.id', 'p.content', 'p.postedAt', 'p.voteCount', 'p.state'))
            ->addSelect('(SELECT v.active
                           FROM AppBundle:Vote v
                           WHERE v.post = p.id 
                           AND v.user = :user_id)
                           as voted')
            ->orderBy('p.id', 'DESC')
            ->setParameter('user_id', $user->getId())
            ->setFirstResult($offset)
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult();
    }

    public function findAllOrderByVoteCount(int $offset = 0)
    {
        return $this->findBy(
            array(),
            array('voteCount' => 'DESC'),
            10,
            $offset
        );
    }

    public function findAllWithVotedOrderByVoteCount(int $offset = 0, User $user)
    {
        return $this
            ->createQueryBuilder('p')
            ->select(array('p.id', 'p.content', 'p.postedAt', 'p.voteCount', 'p.state'))
            ->addSelect('(SELECT v.active
                           FROM AppBundle:Vote v
                           WHERE v.post = p.id 
                           AND v.user = :user_id)
                           as voted')
            ->orderBy('p.voteCount', 'DESC')
            ->setParameter('user_id', $user->getId())
            ->setFirstResult($offset)
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * @param $id Id of post to update
     * @param bool $increment If true voteCount will be incremented, else it will be decremented
     * @return mixed
     */
    private function updateVoteCount($id, $increment = true)
    {
        return $this
            ->createQueryBuilder('p')
            ->update($this->getEntityName(), 'p')
            ->set('p.voteCount', 'p.voteCount' . ($increment ? ' + 1' : ' - 1'))
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    public function updateIncVoteCount($id)
    {
        return $this->updateVoteCount($id, true);
    }

    public function updateDecVoteCount($id)
    {
        return $this->updateVoteCount($id, false);
    }
}
