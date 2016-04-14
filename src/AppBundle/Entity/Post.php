<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikola-miljkovic
 * Date: 4/11/16
 * Time: 1:22 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/** Table posts
 * IDPost
 * IDUser
 * Content
 * Time and Date ~ createdAt
 * VoteCount
 * State
 */

/**
 * AppBundle\Entity\Post
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity
 */
class Post
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(name="posted_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="vote_count", type="integer")
     */
    private $voteCount = 0;

    /**
     * @ORM\Column(name="state", type="smallint")
     */
    private $state = 0;
    
    /**
    * @ORM\PrePersist
    */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }
}