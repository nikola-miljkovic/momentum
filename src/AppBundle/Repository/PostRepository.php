<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

class PostRepository extends EntityRepository
{
    public function findOneWithVote($id, User $user)
    {
        return $this
            ->createQueryBuilder('p')
            ->select(array('p.id', 'p.content', 'p.postedAt as date', 'p.voteCount'))
            ->addSelect('(CASE WHEN (p.user = :user_id) THEN 1 ELSE 0 END) as posted')
            ->addSelect('(SELECT v.active
                           FROM AppBundle:Vote v
                           WHERE v.post = p.id 
                           AND v.user = :user_id)
                           as voted')
            ->where('p.id = :post_id')
            ->andWhere('p.active = true')
            ->setParameter('post_id', $id)
            ->setParameter('user_id', $user->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }

    public function findAllOrderById(int $offset = 0)
    {
        return $this->findBy(
            array('active' => true),
            array('id' => 'DESC'),
            10,
            $offset
        );
    }

    public function findAllWithVotedOrderById(int $offset, User $user)
    {
        try {
            return $this
                ->createQueryBuilder('p')
                ->select(array('p.id', 'p.content', 'p.postedAt as date', 'p.voteCount'))
                ->addSelect('(CASE WHEN (p.user = :user_id) THEN 1 ELSE 0 END) as posted')
                ->addSelect('(SELECT v.active
                           FROM AppBundle:Vote v
                           WHERE v.post = p.id 
                           AND v.user = :user_id)
                           as voted')
                ->where('p.active = true')
                ->orderBy('p.id', 'DESC')
                ->setParameter('user_id', $user->getId())
                ->setFirstResult($offset)
                ->setMaxResults(10)
                ->getQuery()
                ->getScalarResult();
        } catch (\Exception $e) {
            throw new NoResultException;
        }
    }

    public function findAllOrderByVoteCount(int $offset = 0)
    {
        return $this->findBy(
            array('active' => true),
            array('voteCount' => 'DESC'),
            10,
            $offset
        );
    }

    public function findAllWithVotedOrderByVoteCount(int $offset = 0, User $user)
    {
        return $this
            ->createQueryBuilder('p')
            ->select(array('p.id', 'p.content', 'p.postedAt as date', 'p.voteCount'))
            ->addSelect('(CASE WHEN (p.user = :user_id) THEN 1 ELSE 0 END) as posted')
            ->addSelect('(SELECT v.active
                           FROM AppBundle:Vote v
                           WHERE v.post = p.id 
                           AND v.user = :user_id)
                           as voted')
            ->where('p.active = true')
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

    public function countRecentPosts()
    {
        $date = new \DateTime(date("F d Y H:i:s", time() - 86400));

        return $this
            ->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.postedAt > :date')
            ->andWhere('p.active = true')
            ->setParameter('date', $date->getTimestamp())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
