<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 * PostID
 * UserID
 * Date
 * Active(def = 1)
 */

/**
 * Class Vote
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="votes")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id()
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\Id()
     */
    private $user;

    /**
     * @ORM\Column(name="posted_at", type="datetime")
     */
    private $postedAt;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @ORM\PrePersist()
     */
    public function setPostedAtValue()
    {
        $this->postedAt = new \DateTime();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function updatePostVoteCount(LifecycleEventArgs $event)
    {
        $vote = $event->getEntity();
        $postRepository = $event
            ->getEntityManager()
            ->getRepository('AppBundle:Post');

        if ($this->active)
        {
            $postRepository->updateIncVoteCount($vote->getPost());
        }
        else
        {
            $postRepository->updateDecVoteCount($vote->getPost());
        }
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPostedAt()
    {
        return $this->postedAt;
    }

    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }
}

